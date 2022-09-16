<?php

namespace App\Filament\Resources\FeedbackResource\Pages;

use App\Filament\Resources\FeedbackResource;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\RestoreAction;
use Filament\Pages\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedback extends EditRecord
{
    protected static string $resource = FeedbackResource::class;

    protected function getActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', [
            'record' => $this->getRecord(),
        ]);
    }
}
