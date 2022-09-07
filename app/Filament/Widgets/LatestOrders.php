<?php

namespace App\Filament\Widgets;

use App\Models\Settings\GeneralSetting;
use App\Models\Booking;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class LatestOrders extends BaseWidget
{
    public static function getHeading(): ?string
    {
        return __('Recent Bookings');
    }

    protected function getTableQuery(): Builder
    {
        return Activity::query()
            ->with(['subject.package.tour', 'causer'])
            ->where('subject_type', Booking::class)
            ->whereBetween('created_at', [now()->subDays(6), now()]);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('created_at')
                ->label(__('Date'))
                ->sortable()
                ->humanDate(),
            TextColumn::make('subject_type')
                ->toggleable(isToggledHiddenByDefault: true)
                ->label(__('Subject'))
                ->formatStateUsing(function (TextColumn $column) {
                    return trans('constant.model.' . $column->getRecord()->subject_type) ?? __('System');
                }),
            TextColumn::make('causer.name')
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable()
                ->default(__('System'))
                ->label(__('Made By')),
            TextColumn::make('description')
                ->toggleable(isToggledHiddenByDefault: true)
                ->label(__('Description'))
                ->formatStateUsing(function (string $state): string {
                    if (str($state)->contains(['created', 'updated', 'deleted', 'restored'])) {
                        return trans("constant.activity.{$state}");
                    }

                    return $state;
                }),
            TextColumn::make('subject.total_price')
                ->label(__('Price'))
                ->money(app(GeneralSetting::class)->default_currency),
            TextColumn::make('subject.package.tour.name')
                ->searchable()
                ->limit(20)
                ->label(__('Tour')),

        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    public static function canView(): bool
    {
        return auth()->user()
            ->roles
            ->pluck('name')
            ->filter(fn ($name) => str($name)->contains(['Manager', 'Super Admin', 'Staff']))
            ->isNotEmpty();
    }
}
