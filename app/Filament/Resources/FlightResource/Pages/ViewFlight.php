<?php

namespace App\Filament\Resources\FlightResource\Pages;

use App\Filament\Resources\FlightResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFlight extends ViewRecord
{
    protected static string $resource = FlightResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['price'] = $data['price'] / 100;

        return $data;
    }
}
