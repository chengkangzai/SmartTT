<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivitiesRelationManagerResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\PackageResource\Pages;
use App\Filament\Resources\PackageResource\RelationManagers\FlightRelationManager;
use App\Filament\Resources\PackageResource\RelationManagers\PricingsRelationManager;
use App\Models\Airline;
use App\Models\Package;
use App\Models\Settings\PackageSetting;
use App\Models\Tour;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Livewire\Component;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('Features');
    }

    public static function getLabel(): ?string
    {
        return __('Packages');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tour_id')
                    ->relationship('tour', 'name')
                    ->label(__('Tour'))
                    ->required(),
                Forms\Components\DateTimePicker::make('depart_time')
                    ->label(__('Departure Time'))
                    ->rules(['required', 'date', 'after_or_equal:today'])
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('airline_id')
                    ->label(__('Airline'))
                    ->options(Airline::get()->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->hiddenOn('view'),
                Forms\Components\MultiSelect::make('flight_id')
                    ->hiddenOn(['view'])
                    ->relationship('flight', 'name')
                    ->label(__('Flight'))
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->inline(false)
                    ->label(__('Active'))
                    ->required(),
                Forms\Components\Card::make([
                    Forms\Components\Repeater::make('packagePricing')
                        ->relationship('packagePricing')
                        ->label(__('Pricings'))
                        ->schema([
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
                        ])
                        ->collapsed()
                        ->columnSpan(2)
                        ->defaultItems(3)
                        ->default(app(PackageSetting::class)->default_pricing)
                        ->columns(8),
                ])
                    ->hiddenOn(['view', 'edit']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tour.name')
                    ->label(__('Tour'))
                    ->limit(40)
                    ->hidden(fn (Component $livewire) => $livewire instanceof RelationManager)
                    ->searchable(),
                Tables\Columns\TextColumn::make('depart_time')
                    ->label(__('Departure Time'))
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price')),
                Tables\Columns\TextColumn::make('flight.airline')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->label(__('Airline'))
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->visible(auth()->user()->isInternalUser())
                    ->label(__('Active')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn(Package $record) => $record->bookings->count() > 0),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function (Collection $records) {
                        $havePackages = $records->some(function (Package $package) {
                            return $package->bookings->count() > 0;
                        });

                        if ($havePackages) {
                            return Notification::make('cannot_delete')
                                ->danger()
                                ->body(__('Cannot delete records because some of the packages have related bookings.'))
                                ->send();
                        }

                        $records->filter(function (Package $package) {
                            return $package->bookings->count() === 0;
                        })->each(function (Package $tour) {
                            $tour->delete();
                        });

                        return Notification::make('success')
                            ->body(__('filament-support::actions/delete.multiple.messages.deleted'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
                PricingsRelationManager::class,
                FlightRelationManager::class,
            ]
            + (auth()->user()?->can('Audit Package') ? [ActivitiesRelationManager::class] : []);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'view' => Pages\ViewPackage::route('/{record}'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['activePricings', 'bookings'])
            ->when(!auth()->user()->isInternalUser(), function (Builder $query) {
                $query->active();
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
