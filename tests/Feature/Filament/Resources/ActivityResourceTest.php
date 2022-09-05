<?php

use App\Models\User;
use App\Filament\Resources\ActivityResource;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use Spatie\Activitylog\Models\Activity;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;
use function Pest\Livewire\livewire;
use function Pest\Laravel\get;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
    ]);
    actingAs(User::factory()->superAdmin()->create());
});

it('should render index page', function () {
    get(ActivityResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render view page', function () {
    $record = Activity::create(['description' => 'should render view page']);
    get(ActivityResource::getUrl('view', [
        'record' => $record
    ]))->assertSuccessful();
});
