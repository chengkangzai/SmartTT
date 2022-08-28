<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AirportResource\Pages;
use App\Filament\Resources\AirportResource\RelationManagers;
use App\Models\Airport;
use DateTimeZone;
use Filament\Forms;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AirportResource extends Resource
{
    protected static ?string $model = Airport::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('country_id')
                    ->relationship('country', 'name'),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Airport Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('IATA')
                    ->hint('International Air Transport Association code')
                    ->placeholder('3 letter code')
                    ->required()
                    ->label('IATA')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ICAO')
                    ->hint('International Civil Aviation Organization code')
                    ->placeholder('4 letter code')
                    ->required()
                    ->label('ICAO')
                    ->maxLength(255),
                Forms\Components\TextInput::make('latitude')
                    ->hint('Latitude in decimal degrees')
                    ->placeholder('Eg. 1.641310')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('longitude')
                    ->hint('Longitude in decimal degrees')
                    ->placeholder('Eg. 103.669998')
                    ->required(),
                Forms\Components\TextInput::make('altitude')
                    ->hint('Altitude in meters')
                    ->placeholder('Eg -135')
                    ->required(),
                Forms\Components\TextInput::make('DST')
                    ->hint('Daylight Saving Time of the airport')
                    ->required()
                    ->label('DST')
                    ->maxLength(255),
                Forms\Components\Select::make('timezoneTz')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label('Country'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('IATA')
                    ->label('IATA'),
                Tables\Columns\TextColumn::make('ICAO')
                    ->label('ICAO'),
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
            //
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
