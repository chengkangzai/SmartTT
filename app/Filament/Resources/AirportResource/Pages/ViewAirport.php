<?php

namespace App\Filament\Resources\AirportResource\Pages;

use App\Filament\Resources\AirportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAirport extends ViewRecord
{
    protected static string $resource = AirportResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
