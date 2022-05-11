<?php

use App\Actions\Booking\Invoice\GenerateInvoiceAction;
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
        BookingSeeder::class,
    ]);
});

it('should generate invoice', function () {
    $payment = Payment::factory()->create();

    $newPayment = app(GenerateInvoiceAction::class)->execute($payment);

    expect($newPayment)->toBeInstanceOf(Payment::class);
    expect($newPayment->getMedia('invoices'))->not->toBeEmpty();
    expect($newPayment->getMedia('invoices')[0]->getPath())->toContain('_invoice_' . $payment->booking_id . '.pdf');
    expect($newPayment->getMedia('invoices')[0]->getPath())->toContain('public');
});
