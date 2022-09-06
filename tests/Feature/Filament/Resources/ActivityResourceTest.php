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

it('should render activity index page', function () {
    get(ActivityResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render activity view page', function () {
    get(ActivityResource::getUrl('view', [
        'record' => Activity::create(['description' => 'test'])
    ]))->assertSuccessful();
});

it('should render page to view activity record ', function () {
    $activity = Activity::create(['description' => 'test']);

    livewire(ActivityResource\Pages\ViewActivity::class, [
        'record' => $activity->getKey(),
    ])
        ->assertFormSet([
            'description' => $activity->description,
        ]);
});

