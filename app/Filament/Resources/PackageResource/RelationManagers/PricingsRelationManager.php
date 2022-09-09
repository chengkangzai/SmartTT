<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use App\Models\PackagePricing;
use App\Models\Settings\GeneralSetting;
use Closure;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class PricingsRelationManager extends RelationManager
{
    protected static string $relationship = 'packagePricing';

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
                        ->numeric()
                        ->label(__('Price'))
                        ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask->money(app(GeneralSetting::class)->default_currency))
                        ->columnSpan(2)
                        ->required(),
                    Forms\Components\TextInput::make('total_capacity')
                        ->numeric()
                        ->label(__('Capacity'))
                        ->columnSpan(2)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, Closure $set) {
                            $set('available_capacity', $state);
                        }),
                    Forms\Components\Hidden::make('available_capacity'),
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
                    ->money(app(GeneralSetting::class)->default_currency)
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
                Tables\Actions\DeleteAction::make()
                    ->action(function (PackagePricing $packagePricing) {
                        if ($packagePricing->guests->count() > 0) {
                            return Notification::make('cannot_delete')
                                ->danger()
                                ->body(__('Cannot delete records because some of the pricing is in used.'))
                                ->send();
                        }
                        $packagePricing->delete();

                        return Notification::make('success')
                            ->body(__('filament-support::actions/delete.multiple.messages.deleted'))
                            ->success()
                            ->send();
                    })
                ,
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make()
                    ->action(function (Collection $records) {
                        $havePackages = $records->some(function (PackagePricing $pricing) {
                            return $pricing->guests->count() > 0;
                        });

                        if ($havePackages) {
                            return Notification::make('cannot_delete')
                                ->danger()
                                ->body(__('Cannot delete records because some of the pricing is in used.'))
                                ->send();
                        }

                        $records->filter(function (PackagePricing $pricing) {
                            return $pricing->guests->count() === 0;
                        })->each(function (PackagePricing $pricing) {
                            $pricing->delete();
                        });

                        return Notification::make('success')
                            ->body(__('filament-support::actions/delete.multiple.messages.deleted'))
                            ->success()
                            ->send();
                    }),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->when(! auth()->user()->isInternalUser(), function (Builder $query) {
                $query->active();
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
