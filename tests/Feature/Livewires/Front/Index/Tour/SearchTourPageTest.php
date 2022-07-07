<?php

use App\Http\Livewire\Front\Index\Tour\SearchTourPage;
use App\Models\Tour;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\TourSeeder;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed([
        CountrySeeder::class,
        TourSeeder::class,
        AirlineSeeder::class,
        AirportSeeder::class,
        FlightSeeder::class,
        PackageSeeder::class,
    ]);
});


it('should be mountable', function () {
    Livewire::test(SearchTourPage::class)
        ->assertSuccessful();
});

it('should filter as required', function () {
    $tour = Tour::whereHas('activePackages')->active()->first();
    $departTime = $tour->activePackages->sortBy('depart_time');
    Livewire::test(SearchTourPage::class)
        ->set('q', $tour->name)
        ->set('category', $tour->category)
        ->set('dateFrom', $departTime->first()->depart_time->format('Y-m-d'))
        ->set('dateTo', $departTime->last()->depart_time->format('Y-m-d'))
        ->assertSee($tour->name);
});
