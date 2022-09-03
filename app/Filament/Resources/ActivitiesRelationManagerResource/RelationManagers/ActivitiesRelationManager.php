<?php

namespace App\Filament\Resources\ActivitiesRelationManagerResource\RelationManagers;

use App\Filament\Resources\ActivityResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $recordTitleAttribute = 'description';

    public static function getTitle(): string
    {
        return __('Audit Trail');
    }

    public function mount(Activity $activity)
    {
        dd($activity);
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
