<?php

use App\Filament\Pages\Settings\ManagePackageSetting;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;

use function Pest\Laravel\actingAs;
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

it('should be mountable', function () {
    livewire(ManagePackageSetting::class)
        ->assertSuccessful();
});

it('should render page', function () {
    get(ManagePackageSetting::getUrl())
        ->assertSuccessful();
});
