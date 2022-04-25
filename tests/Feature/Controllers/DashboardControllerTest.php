<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(PermissionSeeder::class);
    seed(UserRoleSeeder::class);
});

it('should return a view when its logged in ', function () {
    $this->actingAs(User::find(1))
        ->get('/')
        ->assertStatus(200)
        ->assertViewIs('smartTT.dashboard');
});

it('should redirect when user is not logged in', function () {
    $this->get('/')
        ->assertRedirect('/login')
        ->assertStatus(302);
});
