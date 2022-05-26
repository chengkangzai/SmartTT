<?php

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Package;
use App\Models\PackagePricing;
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
        Flight::class => __('Flight'),
        Package::class => __('Package'),
        PackagePricing::class => __('Package Pricing'),
        Role::class => __('Role'),
        User::class => __('User'),
        Tour::class => __('Tour'),
        TourDescription::class => __('Tour Description'),
    ],
];
