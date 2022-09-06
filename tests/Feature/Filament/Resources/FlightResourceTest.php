<?php

use App\Filament\Resources\FlightResource;
use App\Models\Flight;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use Filament\Pages\Actions\DeleteAction;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;
use function Pest\Livewire\livewire;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
    ]);
    actingAs(User::factory()->superAdmin()->create());
});

it('should render flight index page', function () {
    get(FlightResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render list flight component ', function () {
    $flights = Flight::factory()->count(10)->create();

    livewire(FlightResource\Pages\ListFlights::class)
        ->assertCanSeeTableRecords($flights);
});

it('should render flight create page', function () {
    get(FlightResource::getUrl('create'))
        ->assertSuccessful();
});

it('should create flight', function () {
    $flight = Flight::factory()->make();
    livewire(FlightResource\Pages\CreateFlight::class)
        ->fillForm([
            'airline_id' => $flight->airline_id,
            'price' => $flight->price,
            'departure_airport_id' => $flight->departure_airport_id,
            'arrival_airport_id' => $flight->arrival_airport_id,
            'departure_date' => $flight->departure_date,
            'arrival_date' => $flight->arrival_date,
            'class' => $flight->class,
            'type' => $flight->type,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('flights', [
        'airline_id' => $flight->airline_id,
        'price' => $flight->price * 100,
        'departure_airport_id' => $flight->departure_airport_id,
        'arrival_airport_id' => $flight->arrival_airport_id,
        'departure_date' => $flight->departure_date->toDateTimeString(),
        'arrival_date' => $flight->arrival_date->toDateTimeString(),
        'class' => $flight->class,
        'type' => $flight->type,
    ]);
});

it('can validate input', function () {
    livewire(FlightResource\Pages\CreateFlight::class)
        ->fillForm([
            'airline_id' => null,
            'price' => null,
            'departure_airport_id' => null,
            'arrival_airport_id' => null,
            'departure_date' => null,
            'arrival_date' => null,
            'class' => null,
            'type' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'airline_id',
            'price',
            'departure_airport_id',
            'arrival_airport_id',
            'departure_date',
            'arrival_date',
            'class',
            'type',
        ]);
});

it('should render flight view page', function () {
    get(FlightResource::getUrl('view', [
        'record' => Flight::factory()->create(),
    ]))->assertSuccessful();
});

it('should render page to view flight record ', function () {
    $flight = Flight::factory()->create();

    livewire(FlightResource\Pages\ViewFlight::class, [
        'record' => $flight->getKey(),
    ])
        ->assertFormSet([
            'airline_id' => $flight->airline_id,
            'price' => $flight->price / 100,
            'departure_airport_id' => $flight->departure_airport_id,
            'arrival_airport_id' => $flight->arrival_airport_id,
            'departure_date' => $flight->departure_date,
            'arrival_date' => $flight->arrival_date,
            'class' => $flight->class,
            'type' => $flight->type,
        ]);
});

it('should render flight edit page', function () {
    get(FlightResource::getUrl('edit', [
        'record' => Flight::factory()->create(),
    ]))->assertSuccessful();
});

it('should render page to show flight record in edit view', function () {
    $flight = Flight::factory()->create();

    livewire(FlightResource\Pages\EditFlight::class, [
        'record' => $flight->getKey(),
    ])
        ->assertFormSet([
            'airline_id' => $flight->airline_id,
            'price' => $flight->price / 100,
            'departure_airport_id' => $flight->departure_airport_id,
            'arrival_airport_id' => $flight->arrival_airport_id,
            'departure_date' => $flight->departure_date,
            'arrival_date' => $flight->arrival_date,
            'class' => $flight->class,
            'type' => $flight->type,
        ]);
});

it('should edit flight', function () {
    $flight = Flight::factory()->create();
    $newFlight = Flight::factory()->make();

    livewire(FlightResource\Pages\EditFlight::class, [
        'record' => $flight->getKey(),
    ])
        ->fillForm([
            'airline_id' => $newFlight->airline_id,
            'price' => $newFlight->price,
            'departure_airport_id' => $newFlight->departure_airport_id,
            'arrival_airport_id' => $newFlight->arrival_airport_id,
            'departure_date' => $newFlight->departure_date,
            'arrival_date' => $newFlight->arrival_date,
            'class' => $newFlight->class,
            'type' => $newFlight->type,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($flight->refresh())
        ->airline_id->toBe($flight->airline_id)
        ->price->toBe($flight->price)
        ->departure_airport_id->toBe($flight->departure_airport_id)
        ->arrival_airport_id->toBe($flight->arrival_airport_id)
        ->departure_date->toDateTimeString()->toBe($flight->departure_date->toDateTimeString())
        ->arrival_date->toDateTimeString()->toBe($flight->arrival_date->toDateTimeString())
        ->class->toBe($flight->class)
        ->type->toBe($flight->type);
});

it('should delete flight', function () {
    $flight = Flight::factory()->create();

    livewire(FlightResource\Pages\EditFlight::class, [
        'record' => $flight->getKey(),
    ])
        ->callPageAction(DeleteAction::class);

    assertSoftDeleted($flight);
});


it('should render Relationship Manager', function () {
    $package = Flight::factory()->create();

    $relationships = FlightResource::getRelations();

    foreach ($relationships as $relationship) {
        livewire($relationship, [
            'ownerRecord' => $package,
        ])
            ->assertSuccessful();
    }
});
