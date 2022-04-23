<?php

use App\Actions\Flight\StoreFlightAction;
use App\Models\Flight;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(AirlineSeeder::class);
    seed(AirportSeeder::class);
});

it('should create flight ', function () {

    $data = Flight::factory()->make();

    $action = app(StoreFlightAction::class)->execute($data->toArray());

    expect($action)->toBeInstanceOf(Flight::class);

    assertDatabaseHas('flights', [
        'id' => $action->id,
        'price' => $data->price * 100,
        'departure_date' => $data->departure_date->toDateTimeString(),
        'arrival_date' => $data->arrival_date->toDateTimeString(),
        'departure_airport_id' => $data->departure_airport_id,
        'arrival_airport_id' => $data->arrival_airport_id,
        'airline_id' => $data->airline_id,
        'created_at' => $action->created_at->toDateTimeString(),
        'updated_at' => $action->updated_at->toDateTimeString(),
    ]);
});


