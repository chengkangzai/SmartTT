<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Tour;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->isNot(Role::findByName('Customer'))) {
            return view('home', [
                'userCount' => User::count(),
                'userData' => [
                    'label' => [__('Saturday'), __('Sunday'), __('Monday'), __('Tuesday'), __('Wednesday'), __('Thursday'), __('Friday')],
                    'data' => [
                        User::whereDay('created_at', '=', now()->subDays(6))->count(),
                        User::whereDay('created_at', '=', now()->subDays(5))->count(),
                        User::whereDay('created_at', '=', now()->subDays(4))->count(),
                        User::whereDay('created_at', '=', now()->subDays(3))->count(),
                        User::whereDay('created_at', '=', now()->subDays(2))->count(),
                        User::whereDay('created_at', '=', now()->subDays(1))->count(),
                        User::whereDay('created_at', '=', now())->count(),
                    ]
                ],
                'bookingCount' => Booking::active()->count(),
                'bookingData' => [
                    'label' => [__('Saturday'), __('Sunday'), __('Monday'), __('Tuesday'), __('Wednesday'), __('Thursday'), __('Friday')],
                    'data' => [
                        Booking::whereDay('created_at', '=', now()->subDays(6))->count(),
                        Booking::whereDay('created_at', '=', now()->subDays(5))->count(),
                        Booking::whereDay('created_at', '=', now()->subDays(4))->count(),
                        Booking::whereDay('created_at', '=', now()->subDays(3))->count(),
                        Booking::whereDay('created_at', '=', now()->subDays(2))->count(),
                        Booking::whereDay('created_at', '=', now()->subDays(1))->count(),
                        Booking::whereDay('created_at', '=', now())->count(),
                    ]
                ],
                //replace with income and revenue data
                'tourCount' => Tour::active()->count(),
                'packageCount' => Package::active()->count(),
                'logs' => Activity::where('subject_type', Booking::class)->latest()->paginate(10),
            ]);
        }

        return view('home');

    }
}
