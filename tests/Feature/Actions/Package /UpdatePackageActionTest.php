<?php

use App\Actions\Package\UpdatePackageAction;
use App\Models\Flight;
use App\Models\Package;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\TourSeeder;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(TourSeeder::class);
    seed(AirlineSeeder::class);
    seed(AirportSeeder::class);
    seed(FlightSeeder::class);
});


it('should update package', function () {
    $ori = Package::factory()->create();
    assertModelExists($ori);

    $mockPackage = Package::factory()->make();
    $mockPackage['flights'] = Flight::inRandomOrder()->take(4)->get()->pluck('id')->toArray();

    $action = app(UpdatePackageAction::class)->execute($mockPackage->toArray(), $ori);

    expect($action)->toBeInstanceOf(Package::class)
        ->and($action->id)->toBe($ori->id)
        ->and($action->depart_time->format('Y-m-d H:i:s'))->toBe($mockPackage['depart_time']->format('Y-m-d H:i:s'))
        ->and($action->tour_id)->toBe($mockPackage['tour_id']);
    assertModelExists($action);
    expect($action->flight->count())->toBe(4);
});
