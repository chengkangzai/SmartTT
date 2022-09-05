<?php

use App\Models\User;
use App\Models\Flight;
use App\Filament\Resources\FlightResource;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;
use function Pest\Livewire\livewire;
use function Pest\Laravel\get;

beforeEach(function (){
   seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
    ]);
    actingAs(User::factory()->superAdmin()->create());
});

it('should render index page', function () {
    get(FlightResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render create page', function () {
    get(FlightResource::getUrl('create'))
        ->assertSuccessful();
});

it('should render view page', function () {
    get(FlightResource::getUrl('view', [
        'record' => Flight::factory()->create()
    ]))->assertSuccessful();
});

it('should render edit page', function () {
    get(FlightResource::getUrl('edit', [
        'record' => Flight::factory()->create()
    ]))->assertSuccessful();
});
