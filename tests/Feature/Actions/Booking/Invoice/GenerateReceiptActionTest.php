<?php

use App\Actions\Booking\Invoice\GenerateReceiptAction;
use App\Models\Payment;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\BookingSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\TourSeeder;
use Database\Seeders\UserRoleSeeder;
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

it('should generate receipt', function () {
    $payment = Payment::factory()->create([
        'status' => Payment::STATUS_PAID
    ]);

    $newPayment = app(GenerateReceiptAction::class)->execute($payment);

    expect($newPayment)->toBeInstanceOf(Payment::class);
    expect($newPayment->getMedia('receipts'))->not->toBeEmpty();
    expect($newPayment->getMedia('receipts')[0]->getPath())->toContain('_receipt_' . $payment->booking_id . '.pdf');
    expect($newPayment->getMedia('receipts')[0]->getPath())->toContain('public');
});

it('should not generate receipt because its not paid', function ($statuses) {
    foreach ($statuses as $status) {
        $payment = Payment::factory()->create([
            'status' => $status
        ]);

        expect(fn() => app(GenerateReceiptAction::class)->execute($payment))
            ->toThrow('Payment is not paid');
    }
})->with([
    [[Payment::STATUS_FAILED, Payment::STATUS_PENDING]]
]);
