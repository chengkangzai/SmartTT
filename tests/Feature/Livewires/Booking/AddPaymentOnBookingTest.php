<?php

use App\Http\Livewire\Booking\AddPaymentOnBooking;
use App\Models\User;

it('should be mountable', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(AddPaymentOnBooking::class)
        ->assertSuccessful();
});
// Test Payment mode base on user mode
