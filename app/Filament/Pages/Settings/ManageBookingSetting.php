<?php

namespace App\Filament\Pages\Settings;

use App\Models\Settings\BookingSetting;
use Illuminate\Support\Facades\Cache;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;

class ManageBookingSetting extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = BookingSetting::class;

    protected static ?int $navigationSort = 5;

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
        return __('Booking Settings');
    }

    protected function getTitle(): string
    {
        return __('Booking Settings');
    }

    protected function getFormSchema(): array
    {
        $paymentMethods = collect(app(BookingSetting::class)->supported_payment_method)
            ->mapWithKeys(fn ($item) => [$item => __('setting.booking.available_payment_method.'.$item)])
            ->toArray();

        return [
            TextInput::make('charge_per_child')
                ->label(__('setting.booking.charge_per_child'))
                ->required()
                ->disabled(auth()->user()->cannot('Edit Setting'))
                ->numeric(),
            TextInput::make('reservation_charge_per_pax')
                ->label(__('setting.booking.reservation_charge_per_pax'))
                ->required()
                ->disabled(auth()->user()->cannot('Edit Setting'))
                ->numeric(),
            Select::make('default_payment_method')
                ->label(__('setting.booking.default_payment_method'))
                ->required()
                ->disabled(auth()->user()->cannot('Edit Setting'))
                ->options($paymentMethods),
        ];
    }

    public function afterSave(): void
    {
        Cache::clear();
    }
}
