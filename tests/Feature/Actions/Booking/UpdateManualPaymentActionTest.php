<?php

use App\Actions\Booking\UpdateManualPaymentAction;
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
it('should update payment w/ card', function () {
    $payment = Payment::factory()->create([
        'payment_method' => Payment::METHOD_CARD,
    ]);
    $user = User::factory()->create();

    $payment = app(UpdateManualPaymentAction::class)
        ->execute($payment, Payment::METHOD_CARD, $user, [
            'card_holder_name' => 'John Doe',
            'card_number' => '1234567890123456',
            'card_expiry_date' => '12/25',
            'card_cvc' => '123',
            'amount' => 100,
            'payment_type' => Payment::TYPE_RESERVATION,
            'billing_name' => 'John Doe',
            'billing_phone' => '123456789',
        ]);

    assertModelExists($payment);
    expect($payment->card_holder_name)->toBe('John Doe');
    expect($payment->card_number)->toBe('1234567890123456');
    expect($payment->card_expiry_date)->toBe('12/25');
    expect($payment->card_cvc)->toBe('123');
    expect($payment->amount)->toBe(100);
    expect($payment->payment_type)->toBe(Payment::TYPE_RESERVATION);
    expect($payment->billing_name)->toBe('John Doe');
    expect($payment->billing_phone)->toBe('123456789');
});

it('should update payment w/ cash', function () {
    $payment = Payment::factory()->create([
        'payment_method' => Payment::METHOD_CASH,
    ]);
    $user = User::factory()->create();

    $payment = app(UpdateManualPaymentAction::class)
        ->execute($payment, Payment::METHOD_CASH, $user, [
            'amount' => 100,
            'payment_type' => Payment::TYPE_RESERVATION,
            'billing_name' => 'John Doe',
            'billing_phone' => '123456789',
            'paymentCashReceived' => true,
        ]);

    assertModelExists($payment);
    expect($payment->amount)->toBe(100);
    expect($payment->payment_type)->toBe(Payment::TYPE_RESERVATION);
    expect($payment->billing_name)->toBe('John Doe');
    expect($payment->billing_phone)->toBe('123456789');
});

it('should throw exception when method is not support ', function () {
    $payment = Payment::factory()->create();
    $user = User::factory()->create();

    expect(function () use ($payment, $user) {
        app(UpdateManualPaymentAction::class)->execute($payment, 'ad', $user, []);
    })->toThrow('Payment method not supported');
});
