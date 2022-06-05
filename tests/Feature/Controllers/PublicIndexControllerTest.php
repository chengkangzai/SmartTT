<?php

use App\Http\Livewire\Front\Index\Tour\PackagesTable;
use App\Models\Tour;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\TourSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
        CountrySeeder::class,
        TourSeeder::class,
        AirlineSeeder::class,
        AirportSeeder::class,
        FlightSeeder::class,
        PackageSeeder::class,
    ]);
});

it('should return index view', function () {
    $this
        ->get(route('front.index'))
        ->assertViewIs('front.index.index');
});

it('should return search view', function () {
    $tour = Tour::whereHas('activePackages')->first()->load([
        'activePackages:id,tour_id,depart_time,is_active',
        'activePackages.pricings:id,price,name,package_id,available_capacity',
        'activePackages.flight:id,departure_airport_id,arrival_airport_id,airline_id',
        'activePackages.flight.airline:id,country_id,name',
        'description',
        'countries:id,name',
    ]);
    $this
        ->get(route('front.tours', $tour))
        ->assertViewIs('front.index.tours')
        ->assertSeeLivewire(PackagesTable::class)
        ->assertViewHas([
            'tour' => $tour,
            'des' => $tour->description->map(function ($description) {
                return [
                    'question' => $description->place,
                    'answer' => $description->description,
                ];
            }),
        ]);
});
