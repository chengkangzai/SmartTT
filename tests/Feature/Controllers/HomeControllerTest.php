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
        ->get(route('home'))
        ->assertStatus(200)
        ->assertViewIs('home');
});

it('should redirect when user is not logged in', function () {
    $this->get(route('home'))
        ->assertRedirect('/login')
        ->assertStatus(302);
});
