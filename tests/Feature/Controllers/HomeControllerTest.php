<?php

use App\Models\User;

it('should return a view when its logged in', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertStatus(200)
        ->assertViewIs('home');
});

it('should redirect when user is not logged in', function () {
    $this->get(route('home'))
        ->assertRedirect('/login')
        ->assertStatus(302);
});
