<?php

use App\Actions\Role\DetachUserToRoleAction;
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

    $role = Role::findByName('Manager');
    assertModelExists($role);

    $user->assignRole($role);
    assert($user->roles()->count() === 1);

    $action = app(DetachUserToRoleAction::class)->execute(['user_id'=>$user->id], $role);
    expect($action)->toBeInstanceOf(Role::class);
    assert($user->roles()->count() === 0);
});
