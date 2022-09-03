<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use App\Filament\Resources\TourResource;
use App\Models\Package;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageRelationManager extends RelationManager
{
    protected static string $relationship = 'package';

    protected static ?string $recordTitleAttribute = 'depart_time';

    public static function getTitle(): string
    {
        return __('Packages');
    }

    protected static function getRecordLabel(): ?string
    {
        return __('Package');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tour_id')
                    ->label(__('Tour'))
                    ->relationship('tour', 'name')
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\DateTimePicker::make('depart_time')
                    ->label(__('Departure Time'))
                    ->rules(['required', 'date', 'after_or_equal:today'])
                    ->reactive()
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('Active'))
                    ->inline(false)
                    ->required(),
                Forms\Components\MultiSelect::make('flight_id')
                    ->relationship('flight', 'name')
                    ->label(__('Flight'))
                    ->columnSpan(2)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tour.name')
                    ->label(__('Tour'))
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('depart_time')
                    ->label(__('Departure Time'))
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label(__('Active')),
                Tables\Columns\TextColumn::make('flight.airline')
                    ->label(__('Airline'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ViewAction::make('View Tour')
                    ->label(__('Tour'))
                    ->url(fn (Package $record) => TourResource::getUrl('view', ['record' => $record->tour_id])),
                Tables\Actions\ViewAction::make('View Itinerary')
                    ->label(__('Itinerary'))
                    ->url(fn (Package $record) => $record->tour->getFirstMediaUrl('itinerary'))
                    ->openUrlInNewTab(),
                Tables\Actions\RestoreAction::make(),
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
