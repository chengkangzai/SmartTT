<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use App\Models\Settings\GeneralSetting;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class GuestRelationManager extends RelationManager
{
    protected static string $relationship = 'guests';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(): string
    {
        return __('Guests');
    }

    protected static function getRecordLabel(): ?string
    {
        return __('Guest');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label(__('Phone'))
                    ->required()
                    ->maxLength(255)
                    ->hiddenOn('edit'),
                Forms\Components\TextInput::make('price')
                    ->label(__('Price'))
                    ->label('Price')
                    ->required()
                    ->maxLength(255)
                    ->hiddenOn('edit'),
                Forms\Components\Toggle::make('is_child')
                    ->label(__('Is Child'))
                    ->inline(false)
                    ->required()
                    ->hiddenOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_child')
                    ->boolean()
                    ->label(__('Is Child')),
                Tables\Columns\TextColumn::make('packagePricing.price')
                    ->label(__('Price'))
                    ->money(app(GeneralSetting::class)->default_currency),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }
}
