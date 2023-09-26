<?php

namespace App\Filament\Resources\TourResource\RelationManagers;

use App\Filament\Resources\PackageResource;
use App\Models\Settings\GeneralSetting;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackagesRelationManager extends RelationManager
{
    protected static string $relationship = 'packages';

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
        return PackageResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return PackageResource::table($table);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->with('packagePricing')
            ->when(! auth()->user()->isInternalUser(), function (Builder $query) {
                $query->active();
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    protected function canView(Model $record): bool
    {
        return app(GeneralSetting::class)->site_mode !== 'Enquiry';
    }
}
