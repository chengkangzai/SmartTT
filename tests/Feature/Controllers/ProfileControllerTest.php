<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(PermissionSeeder::class);
    seed(UserRoleSeeder::class);
    $this->actingAs(User::first());
});

it('should show user profile', function () {
    $this->get(route('profile.show'))
        ->assertSuccessful()
        ->assertViewIs('auth.profile');
});


it('should update profile', function () {
    $user = User::factory()->make()->toArray();
    $user['password'] = 'password';
    $user['password_confirmation'] = 'password';
    $this->from(route('profile.show'))
        ->put(route('profile.update'), $user)
        ->assertRedirect(route('profile.show'))
        ->assertSessionHas('success');

    $updated = User::first();
    expect($updated->name)->toBe($user['name']);
    expect($updated->email)->toBe($user['email']);
});
