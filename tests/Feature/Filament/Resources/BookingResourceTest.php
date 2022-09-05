<?php

use App\Models\User;
use App\Models\Booking;
use App\Filament\Resources\BookingResource;
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
    get(BookingResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render create page', function () {
    get(BookingResource::getUrl('create'))
        ->assertSuccessful();
});

it('should render view page', function () {
    get(BookingResource::getUrl('view', [
        'record' => Booking::factory()->create()
    ]))->assertSuccessful();
});

it('should render edit page', function () {
    get(BookingResource::getUrl('edit', [
        'record' => Booking::factory()->create()
    ]))->assertSuccessful();
});
