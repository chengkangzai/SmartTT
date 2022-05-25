<?php

use App\Http\Livewire\Booking\CreateBookingWizard;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Settings\BookingSetting;
use App\Models\Settings\GeneralSetting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed([
        DatabaseSeeder::class,
    ]);
    $this->actingAs(User::first());
});

it('should return index view', function () {
    $this
        ->get(route('bookings.index'))
        ->assertViewIs('smartTT.booking.index')
        ->assertViewHas([
            'bookings',
            'setting' => app(GeneralSetting::class),
        ]);
});

it('should return create view', function () {
    $this
        ->get(route('bookings.create'))
        ->assertViewIs('smartTT.booking.create')
        ->assertSeeLivewire(CreateBookingWizard::class);
});

it('should return show view', function () {
    $booking = Booking::first();
    $this
        ->get(route('bookings.show', $booking))
        ->assertViewIs('smartTT.booking.show')
        ->assertViewHas('booking', $booking)
        ->assertViewHas([
            'setting' => app(GeneralSetting::class),
            'bookingSetting' => app(BookingSetting::class),
        ]);
});

it('should return audit view', function () {
    $this
        ->get(route('bookings.audit', Booking::first()))
        ->assertViewIs('smartTT.booking.audit')
        ->assertViewHas('booking', Booking::first())
        ->assertViewHas('logs');
});

it('should return add payment view', function () {
    $booking = Booking::factory()->afterCreating(function (Booking $b) {
        $b->payment()->save(Payment::factory()->make([
            'status' => Payment::STATUS_FAILED,
            'amount' => 10,
        ]));
    })
        ->create();
    $this
        ->get(route('bookings.addPayment', $booking))
        ->assertViewIs('smartTT.booking.add-payment')
        ->assertSeeLivewire('booking.add-payment-on-booking');
});

it('should not return payment view bc its fully paid', function () {
    $booking = Booking::factory()->afterCreating(function (Booking $b) {
        Payment::factory()->create([
            'booking_id' => $b->id,
            'status' => Payment::STATUS_PAID,
            'amount' => $b->total_price,
        ]);
    })
        ->create();
    $this
        ->get(route('bookings.addPayment', $booking))
        ->assertRedirect(route('bookings.show', $booking))
        ->assertDontSeeLivewire('booking.add-payment-on-booking')
        ->assertSessionHasErrors();
});

it('should destroy a booking', function () {
    $booking = Booking::factory()->create();
    assertModelExists($booking);

    $this
        ->delete(route('bookings.destroy', $booking))
        ->assertRedirect(route('bookings.index'))
        ->assertSessionHas('success');

    assertSoftDeleted($booking);
});
