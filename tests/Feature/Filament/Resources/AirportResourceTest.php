<?php

use App\Models\User;
use App\Models\Airport;
use App\Filament\Resources\AirportResource;
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
    get(AirportResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render create page', function () {
    get(AirportResource::getUrl('create'))
        ->assertSuccessful();
});

it('should render view page', function () {
    get(AirportResource::getUrl('view', [
        'record' => Airport::factory()->create()
    ]))->assertSuccessful();
});

it('should render edit page', function () {
    get(AirportResource::getUrl('edit', [
        'record' => Airport::factory()->create()
    ]))->assertSuccessful();
});
