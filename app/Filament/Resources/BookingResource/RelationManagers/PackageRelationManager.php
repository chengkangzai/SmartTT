<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use App\Models\Airline;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageRelationManager extends RelationManager
{
    protected static string $relationship = 'package';

    protected static ?string $recordTitleAttribute = 'depart_time';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tour_id')
                    ->relationship('tour', 'name')
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\DateTimePicker::make('depart_time')
                    ->rules(['required', 'date', 'after_or_equal:today'])
                    ->reactive()
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->inline(false)
                    ->required(),
                Forms\Components\MultiSelect::make('flight_id')
                    ->relationship('flight', 'name')
                    ->columnSpan(2)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tour.name')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('depart_time')
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\BooleanColumn::make('is_active'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('flight.airline')
                    ->label('Airline')
                    ->sortable()
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\RestoreAction::make(),
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
