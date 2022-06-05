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
    $flight = Flight::factory()->make();

    $action = app(StoreFlightAction::class)->execute($flight->toArray());

    expect($action)->toBeInstanceOf(Flight::class)
        ->and($action->id)->toBeGreaterThan(0)
        ->and($action->airline_id)->toBe($flight->airline_id)
        ->and($action->departure_airport_id)->toBe($flight->departure_airport_id)
        ->and($action->arrival_airport_id)->toBe($flight->arrival_airport_id)
        ->and($action->price)->toBe($flight->price)
        ->and($action->class)->toBe($flight->class)
        ->and($action->type)->toBe($flight->type);

    assertModelExists($action);
});
