<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivitiesRelationManagerResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\FlightResource\Pages;
use App\Filament\Resources\FlightResource\RelationManagers\AirlineRelationManager;
use App\Models\Flight;
use App\Models\Settings\FlightSetting;
use App\Models\Settings\GeneralSetting;
use Filament\Forms;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component;

class FlightResource extends Resource
{
    protected static ?string $model = Flight::class;

    protected static ?string $navigationIcon = 'maki-airport';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('Features');
    }

    public static function getLabel(): ?string
    {
        return __('Flights');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('airline_id')
                    ->relationship('airline', 'name')
                    ->label(__('Airline'))
                    ->searchable()
                    ->required()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('price')
                    ->label(__('Price'))
                    ->columnSpan(2)
                    ->mask(fn (Mask $mask) => $mask->money(app(GeneralSetting::class)->default_currency))
                    ->required(),
                Forms\Components\Select::make('departure_airport_id')
                    ->relationship('depart_airport', 'name')
                    ->label(__('Departure Airport'))
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('arrival_airport_id')
                    ->relationship('arrive_airport', 'name')
                    ->label(__('Arrival Airport'))
                    ->searchable()
                    ->required(),
                Forms\Components\DateTimePicker::make('departure_date')
                    ->label(__('Departure Date'))
                    ->rules(['date', 'after_or_equal:today'])
                    ->required(),
                Forms\Components\DateTimePicker::make('arrival_date')
                    ->label(__('Arrival Date'))
                    ->rules(['date', 'after_or_equal:departure_date'])
                    ->required(),
                Forms\Components\Select::make('class')
                    ->label(__('Flight Class'))
                    ->default(app(FlightSetting::class)->default_class)
                    ->options(app(FlightSetting::class)->supported_class)
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label(__('Flight Type'))
                    ->default(app(FlightSetting::class)->default_type)
                    ->options(app(FlightSetting::class)->supported_type)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('depart_airport.name')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->searchable()
                    ->label(__('Departure Airport')),
                Tables\Columns\TextColumn::make('arrive_airport.name')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->searchable()
                    ->label(__('Arrival Airport')),
                Tables\Columns\TextColumn::make('airline')
                    ->searchable()
                    ->label(__('Airline')),
                Tables\Columns\TextColumn::make('departure_date')
                    ->label(__('Departure Date'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('arrival_date')
                    ->label(__('Arrival Date'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price'))
                    ->visible(auth()->user()->isInternalUser())
                    ->money(app(GeneralSetting::class)->default_currency),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('airline')
                    ->label(__('Airline'))
                    ->relationship('airline', 'name'),
                Tables\Filters\Filter::make('departure_date')
                    ->form([
                        Forms\Components\Card::make([
                            Forms\Components\DatePicker::make('depart_from')
                                ->label(__('Depart From')),
                            Forms\Components\DatePicker::make('depart_until')
                                ->label(__('Depart Until')),
                        ])->columns(2),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['depart_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('departure_date', '>=', $date),
                            )
                            ->when(
                                $data['depart_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('departure_date', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('arrival_date')
                    ->form([
                        Forms\Components\Card::make([
                            Forms\Components\DatePicker::make('arrive_from')
                                ->label(__('Arrive From')),
                            Forms\Components\DatePicker::make('arrive_until')
                                ->label(__('Arrive Until')),
                        ])->columns(2),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['arrive_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('arrival_date', '>=', $date),
                            )
                            ->when(
                                $data['arrive_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('arrival_date', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('price')
                    ->form([
                        Forms\Components\Card::make([
                            Forms\Components\DatePicker::make('price_from')
                                ->label(__('Price From')),
                            Forms\Components\DatePicker::make('price_until')
                                ->label(__('Price Until')),
                        ])->columns(2),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['price_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('price', '>=', $date),
                            )
                            ->when(
                                $data['price_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('price', '<=', $date),
                            );
                    }),
                Tables\Filters\SelectFilter::make('arrive_airport')
                    ->multiple()
                    ->label(__('Arrival Airport'))
                    ->relationship('arrive_airport', 'name'),
                Tables\Filters\SelectFilter::make('depart_airport')
                    ->multiple()
                    ->label(__('Depart Airport'))
                    ->relationship('depart_airport', 'name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn (Component $livewire) => $livewire instanceof RelationManager),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),

            ]);
    }

    public static function getRelations(): array
    {
        return [
            AirlineRelationManager::class,
            ActivitiesRelationManager::class,
        ]
            + (auth()->user()?->can('Audit Flight') ? [ActivitiesRelationManager::class] : []);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFlights::route('/'),
            'create' => Pages\CreateFlight::route('/create'),
            'view' => Pages\ViewFlight::route('/{record}'),
            'edit' => Pages\EditFlight::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canViewAny(): bool
    {
        return parent::canViewAny() && app(GeneralSetting::class)->site_mode !== 'Enquiry';
    }
}
