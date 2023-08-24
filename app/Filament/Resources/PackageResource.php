<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivitiesRelationManagerResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\PackageResource\Pages;
use App\Filament\Resources\PackageResource\RelationManagers\FlightRelationManager;
use App\Filament\Resources\PackageResource\RelationManagers\PricingsRelationManager;
use App\Models\Package;
use App\Models\Settings\PackageSetting;
use Arr;
use Closure;
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
                Forms\Components\Select::make('flight_id')
                    ->multiple()
                    ->hiddenOn(['view'])
                    ->relationship('flight', 'name')
                    ->label(__('Flight')),
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
                                ->reactive()
                                ->numeric()
                                ->afterStateUpdated(function (Closure $get, Closure $set, string $context) {
                                    if ($context === 'create') {
                                        $set('total_capacity', $get('capacity'));
                                        $set('available_capacity', $get('capacity'));
                                    }
                                })
                                ->label(__('Capacity'))
                                ->columnSpan(2)
                                ->required(),
                            Forms\Components\Toggle::make('is_active')
                                ->label(__('Active'))
                                ->columnSpan(1)
                                ->inline(false)
                                ->required(),
                            Forms\Components\Hidden::make('total_capacity'),
                            Forms\Components\Hidden::make('available_capacity'),
                        ])
                        ->collapsible()
                        ->columnSpan(2)
                        ->defaultItems(3)
                        ->default(Arr::map(app(PackageSetting::class)->default_pricing, fn($data) => [
                            ...$data,
                            'total_capacity' => $data['capacity'],
                            'available_capacity' => $data['capacity']
                        ]))
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
                    ->hidden(fn(Component $livewire) => $livewire instanceof RelationManager)
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
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->visible(auth()->user()->isInternalUser())
                    ->label(__('Active')),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active'))
                    ->attribute('is_active'),
                Tables\Filters\SelectFilter::make('tour')
                    ->multiple()
                    ->relationship('tour', 'name'),
                Tables\Filters\Filter::make('depart_time')
                    ->form([
                        Forms\Components\DatePicker::make('depart_from'),
                        Forms\Components\DatePicker::make('depart_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['depart_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('depart_time', '>=', $date),
                            )
                            ->when(
                                $data['depart_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('depart_time', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn(Package $record) => PackageResource::getUrl('view', [
                        'record' => $record,
                    ])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn(Package $record) => $record->bookings->count() > 0),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn(Component $livewire) => $livewire instanceof RelationManager),
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

    protected function getTableFiltersFormColumns(): int
    {
        return 3;
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
