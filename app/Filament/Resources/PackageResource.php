<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivitiesRelationManagerResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\PackageResource\Pages;
use App\Filament\Resources\PackageResource\RelationManagers\FlightRelationManager;
use App\Filament\Resources\PackageResource\RelationManagers\PricingsRelationManager;
use App\Models\Airline;
use App\Models\Package;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                    ->reactive(),
                Forms\Components\MultiSelect::make('flight_id')
                    ->relationship('flight', 'name')
                    ->label(__('Flight'))
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('Active'))
                    ->required(),
                Forms\Components\Card::make([
                    Forms\Components\Repeater::make('pricings')
                        ->relationship('pricings')
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
                        ->columnSpan(2)
                        ->defaultItems(2)
                        ->columns(8),
                ])->hiddenOn('view'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tour.name')
                    ->label(__('Tour'))
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('depart_time')
                    ->label(__('Departure Time'))
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label(__('Active')),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price')),
                Tables\Columns\TextColumn::make('flight.airline')
                    ->label(__('Airline'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PricingsRelationManager::class,
            FlightRelationManager::class,
            ActivitiesRelationManager::class,
        ];
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
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
