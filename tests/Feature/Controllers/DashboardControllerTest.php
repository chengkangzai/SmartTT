<?php

use App\Models\User;

it('should return a view when its logged in ', function () {
    $this->actingAs(User::factory()->create())
        ->get('/')
        ->assertStatus(200)
        ->assertViewIs('smartTT.dashboard');
});

it('should redirect when user is not logged in', function () {
    $this->get('/')
        ->assertRedirect('/login')
        ->assertStatus(302);
});
