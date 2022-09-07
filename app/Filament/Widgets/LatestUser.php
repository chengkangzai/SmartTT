<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestUser extends BaseWidget
{
    protected function getTableQuery(): Builder
    {
        return User::query()->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label(__('Name'))
                ->searchable()
                ->sortable(),
            TextColumn::make('email')
                ->label(__('Email'))
                ->searchable()
                ->sortable(),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()
                ->roles
                ->pluck('name')
                ->filter(fn($name) => str($name)->contains(['Manager', 'Super Admin', 'Staff']))
                ->isNotEmpty();
    }
}
