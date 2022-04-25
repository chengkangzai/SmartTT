<?php

use App\Actions\Role\AttachUserToRoleAction;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    seed(PermissionSeeder::class);
    seed(UserRoleSeeder::class);
});

it('should attach one user to role', function () {
    $user = User::factory()->create();
    assertModelExists($user);
    assert($user->roles()->count() === 0);

    $role = Role::find(2);
    assertModelExists($role);

    $action = app(AttachUserToRoleAction::class)->execute(['users' => [$user->id]], $role);
    expect($action)->toBeInstanceOf(Role::class);
    assert($user->roles()->count() === 1);
});

it('should attach multiple user to one role', function () {
    $users = User::factory()->count(2)->create();
    assert($users->count() === 2);
    $users->each(function ($user) {
        assertModelExists($user);
        assert($user->roles()->count() === 0);
    });

    $role = Role::inRandomOrder()->first();
    assertModelExists($role);

    $action = app(AttachUserToRoleAction::class)
        ->execute(['users' => $users->pluck('id')->toArray()], $role);
    expect($action)->toBeInstanceOf(Role::class);
    $users->each(function ($user) {
        assert($user->roles()->count() === 1);
    });
});
