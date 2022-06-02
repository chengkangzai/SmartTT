<?php

use App\Models\User;

it('should redirect when user is not logged in', function () {
    $this->get(route('home'))
        ->assertRedirect(route('login'))
        ->assertStatus(302);
});

it('should return dashboard view', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertViewIs('home')
        ->assertStatus(200);
});
