<?php

use App\Models\Airline;
use App\Models\Flight;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(DatabaseSeeder::class);
    $this->actingAs(User::first());
});

it('should return index view', function () {
    $this
        ->get(route('flights.index'))
        ->assertViewIs('smartTT.flight.index')
        ->assertViewHas('flights', Flight::with(['airline:id,name', 'depart_airport:id,IATA', 'arrive_airport:id,IATA'])->orderByDesc('id')->paginate(10));
});

it('should return create view', function () {
    $this
        ->get(route('flights.create'))
        ->assertViewIs('smartTT.flight.create')
        ->assertViewHas('airlines', Airline::select(['id', 'name'])->get());
});

it('should return edit view', function () {
    $this
        ->get(route('flights.edit', Flight::first()))
        ->assertViewIs('smartTT.flight.edit')
        ->assertViewHas('flight', Flight::first())
        ->assertViewHas('airlines', Airline::select(['id', 'name'])->get());
});

it('should return show view', function () {
    $this
        ->get(route('flights.show', Flight::first()))
        ->assertViewIs('smartTT.flight.show')
        ->assertViewHas('flight', Flight::first());
});

it('should return audit view', function () {
    $this
        ->get(route('flights.audit', Flight::first()))
        ->assertViewIs('smartTT.flight.audit')
        ->assertViewHas('flight', Flight::first())
        ->assertViewHas('logs');
});

it('should store a flight', function () {
    $flight = Flight::factory()->make();
    $this
        ->post(route('flights.store'), $flight->toArray())
        ->assertRedirect(route('flights.index'))
        ->assertSessionHas('success');

    /** @var Flight $latestFlight */
    $latestFlight = Flight::orderByDesc('id')->get()->first();
    expect($flight->price)->toEqual($latestFlight->price);
    expect($flight->airline_id)->toEqual($latestFlight->airline_id);
    expect($flight->departure_airport_id)->toEqual($latestFlight->departure_airport_id);
    expect($flight->arrival_airport_id)->toEqual($latestFlight->arrival_airport_id);
    expect($flight->class)->toEqual($latestFlight->class);
    expect($flight->type)->toEqual($latestFlight->type);
});

it('should update a flight', function () {
    $flight = Flight::factory()->create();
    assertModelExists($flight);

    $newFlight = Flight::factory()->make();
    $this
        ->put(route('flights.update', $flight), $newFlight->toArray())
        ->assertRedirect(route('flights.index'))
        ->assertSessionHas('success');

    $latestFlight = Flight::find($flight->id);
    expect($newFlight->price)->toEqual($latestFlight->price);
    expect($newFlight->airline_id)->toEqual($latestFlight->airline_id);
    expect($newFlight->departure_airport_id)->toEqual($latestFlight->departure_airport_id);
    expect($newFlight->arrival_airport_id)->toEqual($latestFlight->arrival_airport_id);
    expect($newFlight->class)->toEqual($latestFlight->class);
    expect($newFlight->type)->toEqual($latestFlight->type);
});


it('should destroy a flight', function () {
    $flight = Flight::factory()->create();
    assertModelExists($flight);

    $this
        ->delete(route('flights.destroy', $flight))
        ->assertRedirect(route('flights.index'))
        ->assertSessionHas('success');

    assertSoftDeleted($flight);
});