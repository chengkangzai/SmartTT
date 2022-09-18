<?php

namespace App\Filament\Pages\Settings;

use App\Models\Country;
use App\Models\Settings\GeneralSetting;
use Artisan;
use Carbon\CarbonTimeZone;
use Closure;
use DateTimeZone;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;

class ManageGeneralSetting extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = GeneralSetting::class;

    protected static ?int $navigationSort = 1;

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

        $siteModes = collect(app(GeneralSetting::class)->supported_site_mode)
            ->mapWithKeys(fn($mode) => [$mode => __('setting.general.available_site_mode.' . $mode)]);

        return [
            Tabs::make('Heading')
                ->tabs([
                    Tabs\Tab::make('Site Settings')
                        ->label(__('Site Settings'))
                        ->schema([
                            TextInput::make('site_name')
                                ->label(__('setting.general.site_name'))
                                ->columnSpan(2)
                                ->disabled(auth()->user()->cannot('Edit Setting'))
                                ->required(),
                            Select::make('default_language')
                                ->label(__('setting.general.default_language'))
                                ->options($languages)
                                ->disabled(auth()->user()->cannot('Edit Setting'))
                                ->required(),
                            Select::make('default_timezone')
                                ->label(__('setting.general.default_timezone'))
                                ->searchable()
                                ->afterStateHydrated(function (Closure $set) {
                                    $timezone = app(GeneralSetting::class)->default_timezone->getName();
                                    $set('default_timezone', $timezone);
                                })
                                ->options($timezones)
                                ->disabled(auth()->user()->cannot('Edit Setting'))
                                ->required(),
                            Select::make('default_currency')
                                ->label(__('setting.general.default_currency'))
                                ->searchable()
                                ->reactive()
                                ->afterStateUpdated(function (Closure $get, Closure $set) {
                                    $currency = $get('default_currency');
                                    $symbol = config('money.' . $currency);
                                    $set('default_currency_symbol', $symbol['symbol']);
                                })
                                ->options($currencies)
                                ->disabled(auth()->user()->cannot('Edit Setting'))
                                ->required(),
                            TextInput::make('default_currency_symbol')
                                ->label(__('setting.general.default_currency_symbol'))
                                ->disabled(),
                            Select::make('default_country')
                                ->label(__('setting.general.default_country'))
                                ->searchable()
                                ->options($countries)
                                ->disabled(auth()->user()->cannot('Edit Setting'))
                                ->required(),
                            Select::make('site_mode')
                                ->label(__('setting.general.site_mode'))
                                ->disabled(auth()->user()->cannot('Edit Setting'))
                                ->options($siteModes),
                        ]),
                    Tabs\Tab::make('Company Settings')
                        ->label(__('Company Settings'))
                        ->schema([
                            TextInput::make('company_name')
                                ->label(__('setting.general.company_name'))
                                ->required()
                                ->maxLength(255)
                                ->disabled(auth()->user()->cannot('Edit Setting')),
                            TextInput::make('company_phone')
                                ->label(__('setting.general.company_phone'))
                                ->required()
                                ->maxLength(255)
                                ->disabled(auth()->user()->cannot('Edit Setting')),
                            TextInput::make('company_email')
                                ->label(__('setting.general.company_email'))
                                ->required()
                                ->maxLength(255)
                                ->disabled(auth()->user()->cannot('Edit Setting')),
                            TextInput::make('business_registration_no')
                                ->label(__('setting.general.business_registration_no'))
                                ->required()
                                ->maxLength(255)
                                ->disabled(auth()->user()->cannot('Edit Setting')),
                            Textarea::make('company_address')
                                ->label(__('setting.general.company_address'))
                                ->columnSpan(2)
                                ->required()
                                ->maxLength(255)
                                ->disabled(auth()->user()->cannot('Edit Setting'))
                                ->rows(4),
                        ]),
                    Tabs\Tab::make('Social Settings')
                        ->label(__('Social Settings'))
                        ->schema([
                            Card::make([
                                Toggle::make('facebook_enable')
                                    ->label(__('setting.general.facebook_enable'))
                                    ->disabled(auth()->user()->cannot('Edit Setting'))
                                    ->columnSpan(1)
                                    ->required()
                                    ->inline(false),
                                TextInput::make('facebook_link')
                                    ->label(__('setting.general.facebook_link'))
                                    ->disabled(auth()->user()->cannot('Edit Setting'))
                                    ->columnSpan(3)
                                    ->required(),
                            ])->columns(4),

                            Card::make([
                                Toggle::make('whatsapp_enable')
                                    ->label(__('setting.general.whatsapp_enable'))
                                    ->disabled(auth()->user()->cannot('Edit Setting'))
                                    ->columnSpan(1)
                                    ->required()
                                    ->inline(false),
                                TextInput::make('whatsapp_link')
                                    ->label(__('setting.general.whatsapp_link'))
                                    ->disabled(auth()->user()->cannot('Edit Setting'))
                                    ->hint('https://faq.whatsapp.com/452366545421244/?locale=en_US')
                                    ->columnSpan(3)
                                    ->required(),
                            ])->columns(4),

                            Card::make([
                                Toggle::make('instagram_enable')
                                    ->label(__('setting.general.instagram_enable'))
                                    ->disabled(auth()->user()->cannot('Edit Setting'))
                                    ->columnSpan(1)
                                    ->required()
                                    ->inline(false),
                                TextInput::make('instagram_link')
                                    ->label(__('setting.general.instagram_link'))
                                    ->disabled(auth()->user()->cannot('Edit Setting'))
                                    ->columnSpan(3)
                                    ->required(),
                            ])->columns(4),

                            Card::make([
                                Toggle::make('twitter_enable')
                                    ->label(__('setting.general.twitter_enable'))
                                    ->disabled(auth()->user()->cannot('Edit Setting'))
                                    ->columnSpan(1)
                                    ->required()
                                    ->inline(false),
                                TextInput::make('twitter_link')
                                    ->label(__('setting.general.twitter_link'))
                                    ->disabled(auth()->user()->cannot('Edit Setting'))
                                    ->columnSpan(3)
                                    ->required(),
                            ])->columns(4),
                        ]),
                    Tabs\Tab::make('Feature Settings')
                        ->label(__('Feature Settings'))
                        ->schema([
                            Toggle::make('chat_bot_enable')
                                ->label(__('setting.general.chat_bot_enable'))
                                ->disabled(auth()->user()->cannot('Edit Setting'))
                                ->columnSpan(1)
                                ->required()
                                ->inline(false),
                            Toggle::make('multi_language_enable')
                                ->label(__('setting.general.multi_language_enable'))
                                ->disabled(auth()->user()->cannot('Edit Setting'))
                                ->columnSpan(1)
                                ->required()
                                ->inline(false),
                            Toggle::make('registration_enable')
                                ->label(__('setting.general.registration_enable'))
                                ->disabled(auth()->user()->cannot('Edit Setting'))
                                ->columnSpan(1)
                                ->required()
                                ->inline(false),
                        ])
                        ->columns(3),
                ])
                ->columns(2)
                ->columnSpan(2),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['default_timezone'] = CarbonTimeZone::create($data['default_timezone']);

        if (app(GeneralSetting::class)->registration_enable != $data['registration_enable']) {
            $enable = $data['registration_enable'] ? 'true' : 'false';
            $envFile = file_get_contents(base_path('.env'));
            $envFile = preg_replace('/APP_ENABLE_REGISTRATION=(.*)/', 'APP_ENABLE_REGISTRATION=' . $enable, $envFile);
            file_put_contents(base_path('.env'), $envFile);
        }

        Artisan::call('cache:clear');

        return $data;
    }
}
