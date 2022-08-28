<?php

use App\Http\Livewire\Front\Index\Index\FeaturedTour;
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
    $tour = Tour::query()
        ->with([
            'media',
            'activePackages:id,is_active,depart_time',
            'activePackages.pricings:id,price,package_id',
            'countries:id,name',
        ])
        ->active()
        ->whereHas('activePackages.pricings')
        ->select(['id', 'name'])
        ->limit(6)
        ->get();
    Livewire::test(FeaturedTour::class)
        ->assertViewIs('livewire.front.index.index.featured-tour')
        ->assertSee($tour[0]->name)
        ->assertSee($tour[1]->name)
        ->assertSee($tour[2]->name)
        ->assertSee($tour[3]->name)
        ->assertSee($tour[4]->name)
        ->assertSee($tour[5]->name)
        ->assertSee(route('front.tours', $tour[0]->id))
        ->assertSee(route('front.tours', $tour[1]->id))
        ->assertSee(route('front.tours', $tour[2]->id))
        ->assertSee(route('front.tours', $tour[3]->id))
        ->assertSee(route('front.tours', $tour[4]->id))
        ->assertSee(route('front.tours', $tour[5]->id))
        ->assertSuccessful()
        ->assertSee('Featured Tours');
});


it('should load more tours', function () {
    Livewire::test(FeaturedTour::class)
        ->assertViewIs('livewire.front.index.index.featured-tour')
        ->assertCount('tours', 6)
        ->assertSet('stillCanLoad', true)
        ->call('loadMore')
        ->assertCount('tours', 12)
        ->assertSet('stillCanLoad', true)
        ->call('loadMore')
        ->assertCount('tours', Tour::whereHas('activePackages')->count())
        ->assertSet('stillCanLoad', false)
        ->assertDontSee('Load More')
        ->assertSuccessful()
        ->assertSee('Featured Tours');
});
