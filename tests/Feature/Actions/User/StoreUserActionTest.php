<?php

use App\Actions\User\StoreUserAction;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(PermissionSeeder::class);
    seed(UserRoleSeeder::class);
});

it('should store a new user', function () {
    $user = User::factory()->make();
    assertModelMissing($user);

    $user = $user->toArray();
    $user['password'] = 'password';
    $user['password_confirmation'] = 'password';

    $storedUser = app(StoreUserAction::class)->execute($user);
    expect($storedUser)->toBeInstanceOf(User::class);
    assertModelExists($storedUser);
    expect($storedUser->roles->first()->name)->toBe('Customer');
});
