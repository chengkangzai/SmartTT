<?php

use App\Models\Booking;
use App\Models\BookingGuest;
use App\Models\Flight;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\Payment;
use App\Models\Tour;
use App\Models\TourDescription;
use App\Models\User;
use Spatie\Permission\Models\Role;

return [
    'activity' => [
        'created' => '被创建',
        'deleted' => '被删除',
        'updated' => '被更新',
    ],
    'model' => [
        Booking::class => __('Booking'),
        BookingGuest::class => __('Guests'),
        Flight::class => __('Flight'),
        Package::class => __('Package'),
        PackagePricing::class => __('Package Pricing'),
        Payment::class => __('Payment'),
        Role::class => __('Role'),
        User::class => __('User'),
        Tour::class => __('Tour'),
        TourDescription::class => __('Tour Description'),
    ],
    ':subject was :description'=>':subject :description',
];
