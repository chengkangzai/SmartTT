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

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected function getFormSchema(): array
    {
        $languages = collect(config('filament-language-switch.locales'))
            ->map(fn ($lang) => $lang['name'])->toArray();

        $timezones = collect(DateTimeZone::listIdentifiers())
            ->mapWithKeys(fn ($timezone) => [$timezone => $timezone])
            ->toArray();

        $currencies = collect(config('money'))
            ->map(fn ($val, $key) => $key)->toArray();

        $countries = Country::all()->pluck('name')
            ->mapWithKeys(fn ($country) => [$country => $country])
            ->toArray();


        return [
            Section::make('Site Settings')
                ->schema([
                    TextInput::make('site_name')
                        ->columnSpan(2)
                        ->label('Site Name')
                        ->required(),
                    Select::make('default_language')
                        ->label('Default Language')
                        ->options($languages)
                        ->required(),
                    Select::make('default_timezone')
                        ->searchable()
                        ->label('Default Timezone')
                        ->afterStateHydrated(function (Closure $set) {
                            $timezone = app(GeneralSetting::class)->default_timezone->getName();
                            $set('default_timezone', $timezone);
                        })
                        ->options($timezones)
                        ->required(),
                    Select::make('default_currency')
                        ->label('Default Currency')
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
                        ->label('Default Country')
                        ->searchable()
                        ->options($countries)
                        ->required(),
                ])->columns(2),
            Section::make('Company Settings')
                ->schema([
                    TextInput::make('company_name')
                        ->label('Company Name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('company_phone')
                        ->label('Company Phone')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('company_email')
                        ->label('Company Email')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('business_registration_no')
                        ->label('Business Registration Number')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('company_address')
                        ->columnSpan(2)
                        ->label('Company Address')
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
