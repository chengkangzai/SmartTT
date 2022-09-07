<?php

namespace App\Filament\Pages\Settings;

use App\Models\Settings\PackageSetting;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

class ManagePackageSetting extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = PackageSetting::class;

    protected static ?int $navigationSort = 3;

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('View Setting');
    }

    public function mount(): void
    {
        abort_unless(auth()->user()->can('View Setting'), 403);
        parent::mount();
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    protected static function getNavigationLabel(): string
    {
        return __('Package Settings');
    }

    protected function getTitle(): string
    {
        return __('Package Settings');
    }

    protected function getFormSchema(): array
    {
        return [
            Toggle::make('default_status')
                ->label(__('Default Status'))
                ->inline(false)
                ->disabled(auth()->user()->cannot('Edit Setting')),
            Card::make([
                TableRepeater::make('default_pricing')
                    ->disabled(auth()->user()->cannot('Edit Setting'))
                    ->label(__('Default Pricing'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Pricing Name'))
                            ->columnSpan(3)
                            ->required(),
                        TextInput::make('capacity')
                            ->label(__('Capacity'))
                            ->columnSpan(2)
                            ->numeric()
                            ->required(),
                        Toggle::make('is_active')
                            ->label(__('Active'))
                            ->inline(false)
                            ->columnSpan(1)
                            ->required(),
                    ])
                    ->collapsible()
                    ->columns(6),
            ]),
        ];
    }
}
