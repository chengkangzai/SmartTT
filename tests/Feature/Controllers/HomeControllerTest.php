<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\seed;

it('should return a view when its logged in', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertStatus(200)
        ->assertViewIs('home');
});

it('should return a view when its logged in as customer', function () {
    seed([
       PermissionSeeder::class,
        UserRoleSeeder::class,
    ]);
    $this->actingAs(User::factory()->customer()->create())
        ->get(route('home'))
        ->assertStatus(200)
        ->assertViewIs('home_customer');
});

it('should redirect when user is not logged in', function () {
    $this->get(route('home'))
        ->assertRedirect('/login')
        ->assertStatus(302);
});
