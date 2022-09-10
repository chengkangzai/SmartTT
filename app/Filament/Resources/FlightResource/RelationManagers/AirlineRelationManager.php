<?php

namespace App\Filament\Resources\FlightResource\RelationManagers;

use App\Models\Airline;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class AirlineRelationManager extends RelationManager
{
    protected static string $relationship = 'airline';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(): string
    {
        return __('Airlines');
    }

    protected static function getRecordLabel(): ?string
    {
        return __('Airline');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('country_id')
                    ->relationship('country', 'name')
                    ->label(__('Country'))
                    ->required(),
                Forms\Components\TextInput::make('ICAO')
                    ->label(__('ICAO'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('IATA')
                    ->label(__('IATA'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label(__('Country'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('ICAO')
                    ->label('ICAO'),
                Tables\Columns\TextColumn::make('IATA')
                    ->label('IATA'),
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
                    ->action(function (Airline $record) {
                        if ($record->flights->count() > 0) {
                            return Notification::make('cannot_delete')
                                ->danger()
                                ->body(__('Cannot delete records because the airline is in used.'))
                                ->send();
                        }
                        $record->delete();

                        return Notification::make('success')
                            ->body(__('filament-support::actions/delete.multiple.messages.deleted'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function (Collection $records) {
                        $havePackages = $records->some(function (Airline $airline) {
                            return $airline->flights->count() > 0;
                        });

                        if ($havePackages) {
                            return Notification::make('cannot_delete')
                                ->danger()
                                ->body(__('Cannot delete records because some of the tours have related packages.'))
                                ->send();
                        }

                        $records->filter(function (Airline $airline) {
                            return $airline->flights->count() === 0;
                        })->each(function (Airline $airline) {
                            $airline->delete();
                        });

                        return Notification::make('success')
                            ->body(__('filament-support::actions/delete.multiple.messages.deleted'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\RestoreBulkAction::make(),
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
