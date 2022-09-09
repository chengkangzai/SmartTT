<?php

namespace App\Filament\Resources\ActivitiesRelationManagerResource\RelationManagers;

use App\Filament\Resources\ActivityResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $recordTitleAttribute = 'description';

    public static function getTitle(): string
    {
        return __('Audit Trail');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return ActivityResource::table($table)
            ->actions([
                Tables\Actions\ViewAction::make('View')
                    ->url(fn ($record) => ActivityResource::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(),
            ]);
    }
}
