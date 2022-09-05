<?php

use App\Models\User;
use App\Models\Tour;
use App\Filament\Resources\TourResource;
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
    get(TourResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render create page', function () {
    get(TourResource::getUrl('create'))
        ->assertSuccessful();
});

it('should render view page', function () {
    get(TourResource::getUrl('view', [
        'record' => Tour::factory()->create()
    ]))->assertSuccessful();
});

it('should render edit page', function () {
    get(TourResource::getUrl('edit', [
        'record' => Tour::factory()->create()
    ]))->assertSuccessful();
});
