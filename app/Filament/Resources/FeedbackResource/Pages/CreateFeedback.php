<?php

namespace App\Filament\Resources\FeedbackResource\Pages;

use App\Filament\Resources\FeedbackResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeedback extends CreateRecord
{
    protected static string $resource = FeedbackResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
