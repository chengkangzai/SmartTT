<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivitiesRelationManagerResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\AirportResource\Pages;
use App\Filament\Resources\AirportResource\RelationManagers\FlightsAsArrivalRelationManager;
use App\Filament\Resources\AirportResource\RelationManagers\FlightsAsDepartureRelationManager;
use App\Models\Airport;
use DateTimeZone;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AirportResource extends Resource
{
    protected static ?string $model = Airport::class;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __('Features');
    }

    public static function getLabel(): ?string
    {
        return __('Airports');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('country_id')
                    ->label(__('Countries'))
                    ->relationship('country', 'name'),
                Forms\Components\TextInput::make('city')
                    ->label(__('City'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label(__('Airport Name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('IATA')
                    ->label(__('IATA'))
                    ->hint(__('International Air Transport Association code'))
                    ->placeholder(__('3 letter code'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ICAO')
                    ->label(__('ICAO'))
                    ->hint(__('International Civil Aviation Organization code'))
                    ->placeholder(__('4 letter code'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('latitude')
                    ->label(__('Latitude'))
                    ->hint(__('Latitude in decimal degrees'))
                    ->placeholder(__('Eg. 1.641310'))
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('longitude')
                    ->label(__('Longitude'))
                    ->hint(__('Longitude in decimal degrees'))
                    ->placeholder(__('Eg. 103.669998'))
                    ->required(),
                Forms\Components\TextInput::make('altitude')
                    ->label(__('Altitude'))
                    ->hint(__('Altitude in meters'))
                    ->placeholder(__('Eg -135'))
                    ->required(),
                Forms\Components\TextInput::make('DST')
                    ->label(__('DST'))
                    ->hint(__('Daylight Saving Time of the airport'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('timezoneTz')
                    ->label(__('Timezone'))
                    ->searchable()
                    ->options(DateTimeZone::listIdentifiers())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Airport Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label(__('Country')),
                Tables\Columns\TextColumn::make('city')
                    ->label(__('City')),
                Tables\Columns\TextColumn::make('IATA')
                    ->label(__('IATA')),
                Tables\Columns\TextColumn::make('ICAO')
                    ->label(__('ICAO')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            FlightsAsArrivalRelationManager::class,
            FlightsAsDepartureRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAirports::route('/'),
            'create' => Pages\CreateAirport::route('/create'),
            'view' => Pages\ViewAirport::route('/{record}'),
            'edit' => Pages\EditAirport::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
