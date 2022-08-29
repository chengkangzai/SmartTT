<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\Airline;
use App\Models\Flight;
use App\Models\Package;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationGroup = 'Features';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tour_id')
                    ->relationship('tour', 'name')
                    ->required(),
                Forms\Components\DateTimePicker::make('depart_time')
                    ->rules(['required', 'date', 'after_or_equal:today'])
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('airline_id')
                    ->options(Airline::get()->pluck('name', 'id'))
                    ->required()
                    ->reactive(),
                Forms\Components\MultiSelect::make('flight_id')
                    ->relationship('flight', 'departure_date')
                    ->getSearchResultsUsing(function (string $search, Closure $get) {
                        $airlineID = $get('airline_id');
                        $departTime = $get('depart_time');
                        if ($airlineID && $departTime) {
                            return Flight::query()
                                ->with('airline')
                                ->where('airline_id', $airlineID)
                                ->where('departure_date', '>=', $departTime)
                                ->get()
                                ->map(function (Flight $flight) {
                                    return $flight->asSelection;
                                });
                        }

                        return [];
                    })
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->asSelection)
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
                Forms\Components\Card::make([
                    Forms\Components\Repeater::make('pricings')
                        ->relationship('pricings')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->columnSpan(3)
                                ->required(),
                            Forms\Components\TextInput::make('price')
                                ->columnSpan(2)
                                ->required(),
                            Forms\Components\TextInput::make('capacity')
                                ->columnSpan(2)
                                ->required(),
                            Forms\Components\Toggle::make('is_active')
                                ->columnSpan(1)
                                ->inline(false)
                                ->required(),
                        ])
                        ->columnSpan(2)
                        ->defaultItems(2)
                        ->columns(8),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tour.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('depart_time')
                    ->dateTime(),
                Tables\Columns\BooleanColumn::make('is_active'),
                Tables\Columns\TextColumn::make('price'),
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
            //
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
