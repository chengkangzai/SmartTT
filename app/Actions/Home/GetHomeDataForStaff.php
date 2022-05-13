<?php

namespace App\Actions\Home;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use JetBrains\PhpStorm\ArrayShape;
use Spatie\Activitylog\Models\Activity;

class GetHomeDataForStaff
{
    public function execute(): array
    {
        return [
            User::count(),
            $this->getUserData(),
            Booking::active()->count(),
            $this->getBookingData(),
            Tour::active()->count(),
            Package::active()->count(),
            $this->getActivityForBooking(),
        ];
    }

    #[ArrayShape(['label' => "array", 'data' => "array"])]
    private function getBookingData(): array
    {
        return [
            'label' => $this->getLastSevenDayInName(),
            'data' => $this->getDataByModelField(Booking::class, 'created_at')
        ];
    }

    #[ArrayShape(['label' => "array", 'data' => "array"])]
    private function getUserData(): array
    {
        return [
            'label' => $this->getLastSevenDayInName(),
            'data' => $this->getDataByModelField(User::class, 'created_at')
        ];
    }

    private function getActivityForBooking(): LengthAwarePaginator
    {
        return Activity::where('subject_type', Booking::class)->latest()->paginate(10, ['*'], 'logs');
    }

    private function getLastSevenDayInName(): array
    {
        return collect()
            ->times(7)
            ->map(fn($i) => now()->subDays($i)->getTranslatedDayName())
            ->toArray();
    }

    private function getDataByModelField(string $model, string $field): array
    {
        /** @var Model $model */
        return [
            $model::whereDay($field, '=', now()->subDays(6))->count(),
            $model::whereDay($field, '=', now()->subDays(5))->count(),
            $model::whereDay($field, '=', now()->subDays(4))->count(),
            $model::whereDay($field, '=', now()->subDays(3))->count(),
            $model::whereDay($field, '=', now()->subDays(2))->count(),
            $model::whereDay($field, '=', now()->subDays(1))->count(),
            $model::whereDay($field, '=', now())->count(),
        ];
    }
}
