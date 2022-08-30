<?php

namespace App\Filament\Pages;

use App\Models\Settings\PackageSetting;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

class ManagePackageSetting extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = PackageSetting::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 3;

    protected function getFormSchema(): array
    {
        return [
            Toggle::make('default_status')
                ->inline(false)
                ->label('Default Status'),
            Card::make([
                TableRepeater::make('default_pricing')
                    ->schema([
                        TextInput::make('name')
                            ->columnSpan(3)
                            ->required(),
                        TextInput::make('capacity')
                            ->columnSpan(2)
                            ->numeric()
                            ->required(),
                        Toggle::make('status')
                            ->inline(false)
                            ->columnSpan(1)
                            ->required(),
                    ])
                    ->collapsible()
                    ->columns(6)
            ])
        ];
    }
}
