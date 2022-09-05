<?php

use App\Models\User;
use App\Models\Package;
use App\Filament\Resources\PackageResource;
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
    get(PackageResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render create page', function () {
    get(PackageResource::getUrl('create'))
        ->assertSuccessful();
});

it('should render view page', function () {
    get(PackageResource::getUrl('view', [
        'record' => Package::factory()->create()
    ]))->assertSuccessful();
});

it('should render edit page', function () {
    get(PackageResource::getUrl('edit', [
        'record' => Package::factory()->create()
    ]))->assertSuccessful();
});
