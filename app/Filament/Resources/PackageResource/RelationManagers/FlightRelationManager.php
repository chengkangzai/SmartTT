<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use App\Filament\Resources\FlightResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FlightRelationManager extends RelationManager
{
    protected static string $relationship = 'flight';

    protected static ?string $recordTitleAttribute = 'airline';

    public static function getTitle(): string
    {
        return __('Flights');
    }

    protected static function getRecordLabel(): ?string
    {
        return __('Flight');
    }

    public static function form(Form $form): Form
    {
        return FlightResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return FlightResource::table($table)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
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
