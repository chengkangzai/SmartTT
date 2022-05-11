<?php

use App\Actions\Booking\CreatePaymentAction;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\BookingSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\TourSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
        CountrySeeder::class,
        TourSeeder::class,
        AirlineSeeder::class,
        AirportSeeder::class,
        FlightSeeder::class,
        PackageSeeder::class,
        BookingSeeder::class
    ]);
});

it('should create payment w/ card', function () {
    $booking = Booking::all()->filter(fn($b) => !$b->isFullPaid())->first();
    $user = User::first();
    $payment = app(CreatePaymentAction::class)
        ->execute(Payment::METHOD_CARD, $booking, $user, [
            'card_holder_name' => 'John Doe',
            'card_number' => '1234567890123456',
            'card_expiry_date' => '12/25',
            'card_cvc' => '123',
            'amount' => 100,
            'payment_type' => Payment::TYPE_REMAINING,
            'billing_name' => 'John Doe',
            'billing_phone' => '123456789',
        ]);

    expect($payment)->toBeInstanceOf(Payment::class);
    assertModelExists($payment);
    expect($payment->payment_method)->toBe(Payment::METHOD_CARD);
    expect($payment->status)->toBe(Payment::STATUS_PAID);
    expect($payment->booking_id)->toBe($booking->id);
    expect($payment->user_id)->toBe($user->id);
    expect($payment->card_holder_name)->toBe('John Doe');
    expect($payment->card_number)->toBe('1234567890123456');
    expect($payment->card_expiry_date)->toBe('12/25');
    expect($payment->card_cvc)->toBe('123');
    expect($payment->amount)->toBe(100);
    expect($payment->payment_type)->toBe(Payment::TYPE_REMAINING);
    expect($payment->billing_name)->toBe('John Doe');
    expect($payment->billing_phone)->toBe('123456789');
});

it('should create payment w/ cash', function () {
    $booking = Booking::all()->filter(fn($b) => !$b->isFullPaid())->first();
    $user = User::first();
    $payment = app(CreatePaymentAction::class)
        ->execute(Payment::METHOD_CASH, $booking, $user, [
            'amount' => 100,
            'payment_type' => Payment::TYPE_REMAINING,
            'billing_name' => 'John Doe',
            'billing_phone' => '123456789',
            'paymentCashReceived' => true,
        ]);

    expect($payment)->toBeInstanceOf(Payment::class);
    assertModelExists($payment);
    expect($payment->payment_method)->toBe(Payment::METHOD_CASH);
    expect($payment->status)->toBe(Payment::STATUS_PAID);
    expect($payment->booking_id)->toBe($booking->id);
    expect($payment->user_id)->toBe($user->id);
    expect($payment->amount)->toBe(100);
    expect($payment->payment_type)->toBe(Payment::TYPE_REMAINING);
    expect($payment->billing_name)->toBe('John Doe');
    expect($payment->billing_phone)->toBe('123456789');
});

it('should throw exception when method is not support ', function () {
    $booking = Booking::all()->filter(fn($b) => !$b->isFullPaid())->first();
    $user = User::first();

    expect(function () use ($booking, $user) {
        app(CreatePaymentAction::class)->execute('asdasd', $booking, $user, []);})
        ->toThrow('Invalid payment method');
});
