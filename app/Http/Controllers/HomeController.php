<?php

namespace App\Http\Controllers;

use App\Actions\Home\GetHomeDataForCustomer;
use App\Actions\Home\GetHomeDataForStaff;

class HomeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->roles->contains('name', 'Customer')) {
            [$userCount, $userData, $bookingCount, $bookingData, $tourCount, $packageCount, $logs, $logData]
                = app(GetHomeDataForStaff::class)->execute();

            return view('home', [
                'userCount' => $userCount,
                'userData' => $userData,
                'bookingCount' => $bookingCount,
                'bookingData' => $bookingData,
                'tourCount' => $tourCount,
                'packageCount' => $packageCount,
                'logs' => $logs,
                'logData' => $logData
            ]);
        }

        [$bookings, $payments] = app(GetHomeDataForCustomer::class)->execute(auth()->user());

        return view('home_customer', [
            'bookings' => $bookings,
            'payments' => $payments,
        ]);
    }
}
