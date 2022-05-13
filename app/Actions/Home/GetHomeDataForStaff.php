<?php

namespace App\Actions\Home;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Tour;
use App\Models\User;
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
            'label' => [__('Saturday'), __('Sunday'), __('Monday'), __('Tuesday'), __('Wednesday'), __('Thursday'), __('Friday')],
            'data' => [
                Booking::whereDay('created_at', '=', now()->subDays(6))->count(),
                Booking::whereDay('created_at', '=', now()->subDays(5))->count(),
                Booking::whereDay('created_at', '=', now()->subDays(4))->count(),
                Booking::whereDay('created_at', '=', now()->subDays(3))->count(),
                Booking::whereDay('created_at', '=', now()->subDays(2))->count(),
                Booking::whereDay('created_at', '=', now()->subDays(1))->count(),
                Booking::whereDay('created_at', '=', now())->count(),
            ],
        ];
    }

    #[ArrayShape(['label' => "array", 'data' => "array"])]
    private function getUserData(): array
    {
        return [
            'label' => [__('Saturday'), __('Sunday'), __('Monday'), __('Tuesday'), __('Wednesday'), __('Thursday'), __('Friday')],
            'data' => [
                User::whereDay('created_at', '=', now()->subDays(6))->count(),
                User::whereDay('created_at', '=', now()->subDays(5))->count(),
                User::whereDay('created_at', '=', now()->subDays(4))->count(),
                User::whereDay('created_at', '=', now()->subDays(3))->count(),
                User::whereDay('created_at', '=', now()->subDays(2))->count(),
                User::whereDay('created_at', '=', now()->subDays(1))->count(),
                User::whereDay('created_at', '=', now())->count(),
            ],
        ];
    }

    private function getActivityForBooking(): array|LengthAwarePaginator
    {
        return Activity::where('subject_type', Booking::class)->latest()->paginate(10, ['*'], 'logs');
    }
}
