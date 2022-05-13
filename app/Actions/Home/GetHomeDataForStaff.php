<?php

namespace App\Actions\Home;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;
use Spatie\Activitylog\Models\Activity;

class GetHomeDataForStaff
{
    private Builder $logs;

    public function execute(): array
    {
        $this->logs = $this->getActivityForBooking();

        return [
            User::count(),
            $this->getUserData(),
            Booking::active()->count(),
            $this->getBookingData(),
            Tour::active()->count(),
            Package::active()->count(),
            $this->logs->paginate(10, ['*'], 'logs'),
            $this->getBooking(),
        ];
    }

    #[ArrayShape(['label' => "array", 'data' => "array"])]
    private function getBookingData(): array
    {
        return [
            'label' => $this->getLastSevenDayInName(),
            'data' => $this->getDataByModelField(Booking::class, 'created_at'),
        ];
    }

    #[ArrayShape(['label' => "array", 'data' => "array"])]
    private function getUserData(): array
    {
        return [
            'label' => $this->getLastSevenDayInName(),
            'data' => $this->getDataByModelField(User::class, 'created_at'),
        ];
    }

    private function getActivityForBooking(): Builder|Activity
    {
        return Activity::where('subject_type', Booking::class)->latest();
    }

    private function getLastSevenDayInName(): array
    {
        return collect()
            ->times(7)
            ->map(fn ($i) => now()->subDays($i)->getTranslatedDayName())
            ->toArray();
    }

    private function getDataByModelField(string $model, string $field): array
    {
        /** @var Booking|User $model */
        return [
            $model::whereDate($field, '=', now()->subDays(6))->count(),
            $model::whereDate($field, '=', now()->subDays(5))->count(),
            $model::whereDate($field, '=', now()->subDays(4))->count(),
            $model::whereDate($field, '=', now()->subDays(3))->count(),
            $model::whereDate($field, '=', now()->subDays(2))->count(),
            $model::whereDate($field, '=', now()->subDays(1))->count(),
            $model::whereDate($field, '=', now())->count(),
        ];
    }

    private function getBooking(): Collection
    {
        return Booking::with('package.tour:id,name')->find($this->logs->pluck('subject_id'));
    }
}
