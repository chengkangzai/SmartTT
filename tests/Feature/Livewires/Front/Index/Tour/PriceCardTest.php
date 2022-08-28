<?php

use App\Http\Livewire\Front\Index\Tour\PriceCard;
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
    $tour = Tour::whereHas('activePackages')->first();
    Livewire::test(PriceCard::class, ['tour' => $tour])
        ->assertSet('tour', $tour)
        ->assertSet('packageId', $tour->activePackages->first()->id)
        ->assertSet(
            'cheapestPackagePricing',
            $tour
            ->activePackages
            ->map(function ($package) {
                return $package->pricings->sortBy('price')->first();
            })
            ->sortBy('price')
            ->first()
        );
});
