<?php

namespace App\Filament\Resources\TourResource\Pages;

use App\Actions\Tour\DeleteTourAction;
use App\Filament\Resources\TourResource;
use App\Models\Tour;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTour extends EditRecord
{
    protected static string $resource = TourResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->action(fn(Tour $record) => app(DeleteTourAction::class)->execute($record)),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', [
            'record' => $this->getRecord(),
        ]);
    }
}
