<?php

namespace App\Filament\Pages\Settings;

use App\Models\Settings\BookingSetting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;

class ManageBookingSetting extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = BookingSetting::class;

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    protected static function getNavigationLabel(): string
    {
        return __('Booking Settings');
    }

    protected function getTitle(): string
    {
        return __('Booking Settings');
    }

    protected function getFormSchema(): array
    {
        $paymentMethods = collect(app(BookingSetting::class)->supported_payment_method)
            ->mapWithKeys(fn ($item) => [$item => $item])
            ->toArray();

        return [
            TextInput::make('charge_per_child')
                ->label(__('Charge Per Child'))
                ->required()
                ->numeric(),
            TextInput::make('reservation_charge_per_pax')
                ->label(__('Reservation Charge Per Pax'))
                ->required()
                ->numeric(),
            Select::make('default_payment_method')
                ->label(__('Default Payment Method'))
                ->required()
                ->options($paymentMethods),
        ];
    }
}
