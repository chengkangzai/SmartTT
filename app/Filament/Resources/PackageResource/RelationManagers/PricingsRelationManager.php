<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PricingsRelationManager extends RelationManager
{
    protected static string $relationship = 'pricings';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(): string
    {
        return __('Package Pricing');
    }

    protected static function getRecordLabel(): ?string
    {
        return __('Package Pricing');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([

                Forms\Components\TextInput::make('name')
                    ->label(__('Pricing Name'))
                    ->columnSpan(3)
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->label(__('Price'))
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\TextInput::make('capacity')
                    ->label(__('Capacity'))
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('Active'))
                    ->columnSpan(1)
                    ->inline(false)
                    ->required(),
                ])->columns(8),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('MYR ')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_capacity')
                    ->label(__('Total Capacity'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('available_capacity')
                    ->label(__('Available Capacity'))
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_active')
                ->label(__('Active')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
