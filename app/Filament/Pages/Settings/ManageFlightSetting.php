<?php

namespace App\Filament\Pages\Settings;

use App\Models\Country;
use App\Models\Settings\FlightSetting;
use Illuminate\Support\Facades\Cache;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Pages\SettingsPage;

class ManageFlightSetting extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = FlightSetting::class;

    protected static ?int $navigationSort = 4;

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
        return __('Flight Settings');
    }

    protected function getTitle(): string
    {
        return __('Flight Settings');
    }

    protected function getFormSchema(): array
    {
        $supportedClass = collect(app(FlightSetting::class)->supported_class)
            ->mapWithKeys(fn ($item) => [$item => $item])
            ->toArray();

        $supportedType = collect(app(FlightSetting::class)->supported_type)
            ->mapWithKeys(fn ($item) => [$item => $item])
            ->toArray();

        $countries = Country::all()->pluck('name')
            ->mapWithKeys(fn ($country) => [$country => $country])
            ->toArray();

        return [
            TagsInput::make('supported_class')
                ->label(__('setting.flight.supported_class'))
                ->suggestions([
                    'Economy' => 'Economy',
                    'Business' => 'Business',
                    'First Class' => 'First Class',
                    'Premium Economy' => 'Premium Economy',
                ])
                ->disabled(auth()->user()->cannot('Edit Setting'))
                ->required(),
            TagsInput::make('supported_type')
                ->label(__('setting.flight.supported_type'))
                ->suggestions([
                    'Round' => 'Round',
                    'One Way' => 'One Way',
                    'Multi-city' => 'Multi-city',
                ])
                ->disabled(auth()->user()->cannot('Edit Setting'))
                ->required(),
            Select::make('default_class')
                ->label(__('setting.flight.default_class'))
                ->options($supportedClass)
                ->disabled(auth()->user()->cannot('Edit Setting'))
                ->required(),
            Select::make('default_type')
                ->label(__('setting.flight.default_type'))
                ->options($supportedType)
                ->disabled(auth()->user()->cannot('Edit Setting'))
                ->required(),
            Select::make('supported_countries')
                ->multiple()
                ->label(__('setting.flight.supported_countries'))
                ->options($countries)
                ->disabled(auth()->user()->cannot('Edit Setting'))
                ->columnSpan(2)
                ->searchable()
                ->required(),
        ];
    }

    public function afterSave(): void
    {
        Cache::clear();
    }
}
