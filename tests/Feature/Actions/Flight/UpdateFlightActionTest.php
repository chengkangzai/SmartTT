<?php

use App\Actions\Flight\UpdateFlightAction;
use App\Models\Flight;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(AirlineSeeder::class);
    seed(AirportSeeder::class);
});

it('should update flight', function () {
    $data = Flight::factory()->create();

    $action = app(UpdateFlightAction::class);
    $mockFlight = Flight::factory()->make()->toArray();
    $newFlight = $action->execute($mockFlight, $data);

    expect($newFlight)->toBeInstanceOf(Flight::class);
    assertModelExists($newFlight);
});
