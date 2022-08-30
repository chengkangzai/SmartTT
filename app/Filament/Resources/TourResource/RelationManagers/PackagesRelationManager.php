<?php

namespace App\Filament\Resources\TourResource\RelationManagers;

use App\Filament\Resources\PackageResource;
use App\Models\Package;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackagesRelationManager extends RelationManager
{
    protected static string $relationship = 'packages';

    protected static ?string $recordTitleAttribute = 'depart_time';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('depart_time')
                    ->rules(['required', 'date', 'after_or_equal:today'])
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->inline(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('depart_time')
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\BooleanColumn::make('is_active'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('flight.airline')
                    ->label('Airline')
                    ->default('-')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Package $record): string => PackageResource::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->with('pricings')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
