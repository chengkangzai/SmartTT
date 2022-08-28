<?php

namespace App\Filament\Resources\AirlineResource\Pages;

use App\Filament\Resources\AirlineResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAirline extends ViewRecord
{
    protected static string $resource = AirlineResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
