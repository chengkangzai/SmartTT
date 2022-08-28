<?php

namespace App\Filament\Resources\FlightResource\Pages;

use App\Filament\Resources\FlightResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFlight extends CreateRecord
{
    protected static string $resource = FlightResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['price'] = $data['price'] * 100;

        return $data;
    }
}
