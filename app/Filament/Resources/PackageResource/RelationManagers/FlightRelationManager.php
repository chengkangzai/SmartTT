<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use App\Models\Settings\FlightSetting;
use Filament\Forms;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FlightRelationManager extends RelationManager
{
    protected static string $relationship = 'flight';

    protected static ?string $recordTitleAttribute = 'airline';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('airline_id')
                    ->relationship('airline', 'name')
                    ->searchable()
                    ->required()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('price')
                    ->columnSpan(2)
                    ->mask(fn (Mask $mask) => $mask->money('MYR '))
                    ->required(),
                Forms\Components\Select::make('departure_airport_id')
                    ->relationship('depart_airport', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('arrival_airport_id')
                    ->relationship('arrive_airport', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\DateTimePicker::make('departure_date')
                    ->rules(['date', 'after_or_equal:today'])
                    ->required(),
                Forms\Components\DateTimePicker::make('arrival_date')
                    ->rules(['date', 'after_or_equal:departure_date'])
                    ->required(),
                Forms\Components\Select::make('class')
                    ->default(app(FlightSetting::class)->default_class)
                    ->options(app(FlightSetting::class)->supported_class)
                    ->required(),
                Forms\Components\Select::make('type')
                    ->default(app(FlightSetting::class)->default_type)
                    ->options(app(FlightSetting::class)->supported_type)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('airline'),
                Tables\Columns\TextColumn::make('price')
                    ->money('MYR '),
                Tables\Columns\TextColumn::make('depart_airport.name'),
                Tables\Columns\TextColumn::make('arrive_airport.name'),
                Tables\Columns\TextColumn::make('departure_date')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('arrival_date')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ])
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
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
