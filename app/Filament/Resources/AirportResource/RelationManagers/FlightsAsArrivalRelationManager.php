<?php

namespace App\Filament\Resources\AirportResource\RelationManagers;

use App\Filament\Resources\FlightResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FlightsAsArrivalRelationManager extends RelationManager
{
    protected static string $relationship = 'flightsAsArrival';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Arrivals';

    public static function getTitle(): string
    {
        return __('Arrivals');
    }

    protected static function getRecordLabel(): ?string
    {
        return __('Flight');
    }

    public static function form(Form $form): Form
    {
        return FlightResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return FlightResource::table($table);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
