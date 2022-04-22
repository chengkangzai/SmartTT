<?php

use App\Actions\Flight\UpdateFlightAction;
use App\Models\Flight;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\CountrySeeder;
use function Pest\Laravel\assertDatabaseHas;

it('should update flight', function () {
    $this->seed(CountrySeeder::class);
    $this->seed(AirlineSeeder::class);
    $this->seed(AirportSeeder::class);

    $data = Flight::factory()->create();
    $action = app(UpdateFlightAction::class);

    $mockFlight = Flight::factory()->make()->toArray();
    $newFlight = $action->execute($mockFlight, $data);

    assertDatabaseHas('flights', [
        'id' => $newFlight->id,
        'price' => $newFlight->price * 100,
        'departure_date' => $newFlight->departure_date->toDateTimeString(),
        'arrival_date' => $newFlight->arrival_date->toDateTimeString(),
        'departure_airport_id' => $newFlight->departure_airport_id,
        'arrival_airport_id' => $newFlight->arrival_airport_id,
        'airline_id' => $newFlight->airline_id,
        'created_at' => $newFlight->created_at->toDateTimeString(),
        'updated_at' => $newFlight->updated_at->toDateTimeString(),
    ]);
});
