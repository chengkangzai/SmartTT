<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Tour;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;


class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '50s';

    protected function getCards(): array
    {
        return [
            Card::make(__('Users'), User::count()),
            Card::make(__('Bookings'), Booking::active()->count()),
            Card::make(__('Tours'), Tour::active()->count()),
            Card::make(__('Packages'), Package::active()->count()),
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
