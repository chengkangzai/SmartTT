<?php

namespace App\Filament\Pages\Settings;

use App\Models\Country;
use App\Models\Settings\GeneralSetting;
use Carbon\CarbonTimeZone;
use Closure;
use DateTimeZone;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;

class ManageGeneralSetting extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = GeneralSetting::class;

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    protected static function getNavigationLabel(): string
    {
        return __('General Settings');
    }

    protected function getTitle(): string
    {
        return __('General Settings');
    }

    protected function getFormSchema(): array
    {
        $languages = collect(config('filament-language-switch.locales'))
            ->map(fn($lang) => $lang['name'])->toArray();

        $timezones = collect(DateTimeZone::listIdentifiers())
            ->mapWithKeys(fn($timezone) => [$timezone => $timezone])
            ->toArray();

        $currencies = collect(config('money'))
            ->map(fn($val, $key) => $key)->toArray();

        $countries = Country::all()->pluck('name')
            ->mapWithKeys(fn($country) => [$country => $country])
            ->toArray();


        return [
            Section::make(__('Site Settings'))
                ->schema([
                    TextInput::make('site_name')
                        ->label(__('Site Name'))
                        ->columnSpan(2)
                        ->required(),
                    Select::make('default_language')
                        ->label(__('Default Language'))
                        ->options($languages)
                        ->required(),
                    Select::make('default_timezone')
                        ->searchable()
                        ->label(__('Default Timezone'))
                        ->afterStateHydrated(function (Closure $set) {
                            $timezone = app(GeneralSetting::class)->default_timezone->getName();
                            $set('default_timezone', $timezone);
                        })
                        ->options($timezones)
                        ->required(),
                    Select::make('default_currency')
                        ->label(__('Default Currency'))
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function (Closure $get, Closure $set) {
                            $currency = $get('default_currency');
                            $symbol = config('money.' . $currency);
                            $set('default_currency_symbol', $symbol['symbol']);
                        })
                        ->options($currencies)
                        ->required(),
                    TextInput::make('default_currency_symbol')
                        ->disabled(),
                    Select::make('default_country')
                        ->label(__('Default Country'))
                        ->searchable()
                        ->options($countries)
                        ->required(),
                ])->columns(2),
            Section::make(__('Company Settings'))
                ->schema([
                    TextInput::make('company_name')
                        ->label(__('Company Name'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('company_phone')
                        ->label(__('Company Phone'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('company_email')
                        ->label(__('Company Email'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('business_registration_no')
                        ->label(__('Business Registration Number'))
                        ->required()
                        ->maxLength(255),
                    Textarea::make('company_address')
                        ->columnSpan(2)
                        ->label(__('Company Address'))
                        ->required()
                        ->maxLength(255)
                        ->rows(4),
                ])->columns(2),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['default_timezone'] = CarbonTimeZone::create($data['default_timezone']);

        return $data;
    }
}
