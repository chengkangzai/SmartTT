<?php

namespace App\Filament\Resources\ActivitiesRelationManagerResource\RelationManagers;

use App\Filament\Resources\ActivityResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                    ->url(fn($record) => ActivityResource::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(),
            ]);
    }

    protected function canCreate(): bool
    {
        return false;
    }

    protected function canEdit(Model $record): bool
    {
        return false;
    }

    protected function canDelete(Model $record): bool
    {
        return false;
    }
}
