<?php

use App\Actions\Flight\UpdateFlightAction;
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

it('should update flight', function () {
    $data = Flight::factory()->create();

    $action = app(UpdateFlightAction::class);
    $mockFlight = Flight::factory()->make()->toArray();
    $newFlight = $action->execute($mockFlight, $data);

    expect($newFlight)->toBeInstanceOf(Flight::class)
        ->and($newFlight->id)->toBe($data->id)
        ->and($newFlight->airline_id)->toBe($mockFlight['airline_id'])
        ->and($newFlight->departure_airport_id)->toBe($mockFlight['departure_airport_id'])
        ->and($newFlight->arrival_airport_id)->toBe($mockFlight['arrival_airport_id'])
        ->and($newFlight->price)->toBe($mockFlight['price'])
        ->and($newFlight->class)->toBe($mockFlight['class'])
        ->and($newFlight->type)->toBe($mockFlight['type']);
    assertModelExists($newFlight);
});
