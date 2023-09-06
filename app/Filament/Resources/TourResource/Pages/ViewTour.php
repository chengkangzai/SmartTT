<?php

namespace App\Filament\Resources\TourResource\Pages;

use App\Filament\Resources\TourResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewTour extends ViewRecord
{
    protected static string $resource = TourResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            Action::make('Itinerary')
                ->label(__('Itinerary'))
                ->icon('heroicon-o-eye')
                ->url(fn () => $this->record->getFirstMediaUrl('itinerary'))
                ->openUrlInNewTab(),
        ];
    }
}
