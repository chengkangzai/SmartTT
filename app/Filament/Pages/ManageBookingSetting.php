<?php

namespace App\Filament\Pages;

use App\Models\Settings\BookingSetting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;

class ManageBookingSetting extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = BookingSetting::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 5;

    protected function getFormSchema(): array
    {
        $paymentMethods = collect(app(BookingSetting::class)->supported_payment_method)
            ->mapWithKeys(fn($item) => [$item => $item])
            ->toArray();

        return [
            TextInput::make('charge_per_child')
                ->required()
                ->numeric(),
            TextInput::make('reservation_charge_per_pax')
                ->required()
                ->numeric(),
            Select::make('default_payment_method')
                ->required()
                ->options($paymentMethods),
        ];
    }
}
