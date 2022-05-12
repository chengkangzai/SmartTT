<?php

use App\Actions\Flight\StoreFlightAction;
use App\Models\Flight;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(AirlineSeeder::class);
    seed(AirportSeeder::class);
});

it('should create flight', function () {
    $data = Flight::factory()->make();

    $action = app(StoreFlightAction::class)->execute($data->toArray());

    expect($action)->toBeInstanceOf(Flight::class);

    assertModelExists($action);
});
