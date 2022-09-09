<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use App\Filament\Resources\FlightResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
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
        return FlightResource::table($table);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
