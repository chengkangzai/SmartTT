<?php


use App\Filament\Resources\BookingResource;
use App\Http\Livewire\Front\Index\Tour\PackagesTable;
use App\Models\Tour;
use Carbon\Carbon;
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
    $pricing = $tour->activePackages->map(fn ($package) => $package->packagePricing->map->price)
        ->flatten()->sort()->values();
    $livewire = Livewire::test(PackagesTable::class, ['tour' => $tour])
        ->assertSet('tour', $tour)
        ->assertSet('packages', $tour->activePackages)
        ->assertSet('priceFrom', $pricing->first())
        ->assertSet('priceTo', $pricing->last())
        ->assertSet('months', $tour->activePackages->pluck('depart_time')->mapWithKeys(fn (Carbon $date) => [
            (int)$date->format('m') => $date->translatedFormat('F'),
        ]))
        ->assertSee('Packages');

    foreach ($tour->activePackages as $package) {
        $livewire->assertSee($package->price)
            ->assertSee(BookingResource::getUrl('create', ['package_id' => $package->id]));
    }
});


it('should filter tour as required', function () {
    $tour = Tour::whereHas('activePackages')->first();
    $livewire = Livewire::test(PackagesTable::class, ['tour' => $tour])
        ->assertSee('Packages');

    $package = $tour->activePackages->first();
    $livewire->set('month', $package->depart_time->month)
        ->assertSee(BookingResource::getUrl('create', ['package_id' => $package->id]));

    $package = $tour->activePackages->last();
    $livewire->set('month', 0)
        ->assertSee(BookingResource::getUrl('create', ['package_id' => $package->id]));
});
