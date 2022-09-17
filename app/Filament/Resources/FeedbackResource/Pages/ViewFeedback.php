<?php

namespace App\Filament\Resources\FeedbackResource\Pages;

use App\Filament\Resources\FeedbackResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFeedback extends ViewRecord
{
    protected static string $resource = FeedbackResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
