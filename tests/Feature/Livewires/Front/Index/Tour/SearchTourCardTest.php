<?php

use App\Http\Livewire\Front\Index\Tour\SearchTourCard;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\TourSeeder;

use function Pest\Laravel\seed;

it('should be mountable', function () {
    seed([
        CountrySeeder::class,
        TourSeeder::class,
        AirlineSeeder::class,
        AirportSeeder::class,
        FlightSeeder::class,
        PackageSeeder::class,
    ]);
    Livewire::test(SearchTourCard::class)
        ->assertSuccessful();
});

it('should render if even no record', function () {
    Livewire::test(SearchTourCard::class)
        ->assertOk()
        ->assertSuccessful();
});
