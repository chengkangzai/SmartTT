<?php

namespace App\Filament\Pages\Settings;

use App\Models\Settings\TourSetting;
use App\Models\Tour;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Illuminate\Validation\ValidationException;

class ManageTourSetting extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = TourSetting::class;

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    protected static function getNavigationLabel(): string
    {
        return __('Tour Settings');
    }

    protected function getTitle(): string
    {
        return __('Tour Settings');
    }

    protected function getFormSchema(): array
    {
        return [
            Toggle::make('default_status')
                ->inline(false)
                ->label(__('Default Status'))
                ->required(),
            TagsInput::make('category')
                ->label(__('Category'))
                ->hint(__('Cannot delete category if at least one tour is in this category'))
                ->required(),
            TextInput::make('default_night')
                ->label(__('Default Night'))
                ->required(),
            TextInput::make('default_day')
                ->label(__('Default Day'))
                ->required(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $categoryBefore = app(TourSetting::class)->category;
        $categoryAfter = $data['category'];
        $deleted = array_diff($categoryBefore, $categoryAfter);

        foreach ($deleted as $deletedCategory) {
            $tagInUse = Tour::where('category', $deletedCategory)->exists();
            if ($tagInUse) {
                Notification::make()
                    ->warning()
                    ->title(__('Category is in use', ['category' => $deletedCategory]))
                    ->body(__('You can not delete this category because it is in use.'))
                    ->send();

                throw ValidationException::withMessages([
                    'category' => __('You can not delete this category because it is in use.'),
                ]);
            }
        }

        return $data;
    }
}
