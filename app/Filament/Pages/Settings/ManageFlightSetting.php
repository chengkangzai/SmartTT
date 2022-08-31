<?php

namespace App\Filament\Pages\Settings;

use App\Models\Country;
use App\Models\Settings\FlightSetting;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Pages\SettingsPage;

class ManageFlightSetting extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = FlightSetting::class;

    protected static ?int $navigationSort = 4;

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
            ->mapWithKeys(fn($item) => [$item => $item])
            ->toArray();

        $supportedType = collect(app(FlightSetting::class)->supported_type)
            ->mapWithKeys(fn($item) => [$item => $item])
            ->toArray();

        $countries = Country::all()->pluck('name')
            ->mapWithKeys(fn($country) => [$country => $country])
            ->toArray();

        return [
            TagsInput::make('supported_class')
                ->label(__('Supported Class'))
                ->suggestions([
                    'Economy' => 'Economy',
                    'Business' => 'Business',
                    'First Class' => 'First Class',
                    'Premium Economy' => 'Premium Economy',
                ])
                ->required(),
            TagsInput::make('supported_type')
                ->label(__('Supported Type'))
                ->suggestions([
                    'Round' => 'Round',
                    'One Way' => 'One Way',
                    'Multi-city' => 'Multi-city',
                ])
                ->required(),
            Select::make('default_class')
                ->label(__('Default Class'))
                ->options($supportedClass)
                ->required(),
            Select::make('default_type')
                ->label(__('Default Type'))
                ->options($supportedType)
                ->required(),
            MultiSelect::make('supported_countries')
                ->label(__('Supported Country'))
                ->options($countries)
                ->columnSpan(2)
                ->searchable()
                ->required(),
        ];
    }
}
