<?php

use App\Http\Livewire\Booking\CreateBookingCard;
use App\Models\User;

it('should be mountable', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(CreateBookingCard::class)
        ->assertSuccessful();
});
// Test Payment mode base on user mode
