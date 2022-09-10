<?php

use App\Filament\Resources\AirportResource;
use App\Models\Airport;
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

it('should render airport index page', function () {
    get(AirportResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render list airport component ', function () {
    $airports = Airport::factory()->count(10)->create();

    livewire(AirportResource\Pages\ListAirports::class)
        ->assertCanSeeTableRecords($airports);
});

it('should render airport create page', function () {
    get(AirportResource::getUrl('create'))
        ->assertSuccessful();
});

it('should create airport', function () {
    $airport = Airport::factory()->make();
    livewire(AirportResource\Pages\CreateAirport::class)
        ->fillForm([
            'city' => $airport->city,
            'name' => $airport->name,
            'IATA' => $airport->IATA,
            'ICAO' => $airport->ICAO,
            'latitude' => $airport->latitude,
            'longitude' => $airport->longitude,
            'altitude' => $airport->altitude,
            'DST' => $airport->DST,
            'timezoneTz' => $airport->timezoneTz,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('airports', [
        'city' => $airport->city,
        'name' => $airport->name,
        'IATA' => $airport->IATA,
        'ICAO' => $airport->ICAO,
        'latitude' => $airport->latitude,
        'longitude' => $airport->longitude,
        'altitude' => $airport->altitude,
        'DST' => $airport->DST,
        'timezoneTz' => $airport->timezoneTz,
    ]);
});

it('can validate input', function () {
    livewire(AirportResource\Pages\CreateAirport::class)
        ->fillForm([
            'city' => null,
            'name' => null,
            'IATA' => null,
            'ICAO' => null,
            'latitude' => null,
            'longitude' => null,
            'altitude' => null,
            'DST' => null,
            'timezoneTz' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'city',
            'name',
            'IATA',
            'ICAO',
            'latitude',
            'longitude',
            'altitude',
            'DST',
            'timezoneTz',
        ]);
});

it('should render airport view page', function () {
    get(AirportResource::getUrl('view', [
        'record' => Airport::factory()->create(),
    ]))->assertSuccessful();
});

it('should render page to view airport record ', function () {
    $airport = Airport::factory()->create();

    livewire(AirportResource\Pages\ViewAirport::class, [
        'record' => $airport->getKey(),
    ])
        ->assertFormSet([
            'city' => $airport->city,
            'name' => $airport->name,
            'IATA' => $airport->IATA,
            'ICAO' => $airport->ICAO,
            'latitude' => $airport->latitude,
            'longitude' => $airport->longitude,
            'altitude' => $airport->altitude,
            'DST' => $airport->DST,
            'timezoneTz' => $airport->timezoneTz,
        ]);
});

it('should render airport edit page', function () {
    get(AirportResource::getUrl('edit', [
        'record' => Airport::factory()->create(),
    ]))->assertSuccessful();
});

it('should render page to show airport record in edit view', function () {
    $airport = Airport::factory()->create();

    livewire(AirportResource\Pages\EditAirport::class, [
        'record' => $airport->getKey(),
    ])
        ->assertFormSet([
            'city' => $airport->city,
            'name' => $airport->name,
            'IATA' => $airport->IATA,
            'ICAO' => $airport->ICAO,
            'latitude' => $airport->latitude,
            'longitude' => $airport->longitude,
            'altitude' => $airport->altitude,
            'DST' => $airport->DST,
            'timezoneTz' => $airport->timezoneTz,
        ]);
});

it('should edit airport', function () {
    $airport = Airport::factory()->create();
    $newAirport = Airport::factory()->make();

    livewire(AirportResource\Pages\EditAirport::class, [
        'record' => $airport->getKey(),
    ])
        ->fillForm([
            'city' => $newAirport->city,
            'name' => $newAirport->name,
            'IATA' => $newAirport->IATA,
            'ICAO' => $newAirport->ICAO,
            'latitude' => $newAirport->latitude,
            'longitude' => $newAirport->longitude,
            'altitude' => $newAirport->altitude,
            'DST' => $newAirport->DST,
            'timezoneTz' => $newAirport->timezoneTz,
        ])
        ->call('save')
        ->assertHasNoFormErrors();
    expect($airport->refresh());
    //;
});

it('should delete airport', function () {
    $airport = Airport::factory()->create();

    livewire(AirportResource\Pages\EditAirport::class, [
        'record' => $airport->getKey(),
    ])
        ->callPageAction(DeleteAction::class);

    assertSoftDeleted($airport);
});

it('should render Relationship Manager', function () {
    $booking = Airport::factory()->create();

    $relationships = AirportResource::getRelations();

    foreach ($relationships as $relationship) {
        livewire($relationship, [
            'ownerRecord' => $booking,
        ])
            ->assertSuccessful();
    }
});
