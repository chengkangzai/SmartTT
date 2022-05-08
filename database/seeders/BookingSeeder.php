<?php

namespace Database\Seeders;

use App\Actions\Booking\Invoice\GenerateInvoiceAction;
use App\Actions\Booking\Invoice\GenerateReceiptAction;
use App\Models\Booking;
use App\Models\BookingGuest;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use function app;
use function collect;

class BookingSeeder extends Seeder
{
    public function run()
    {
        Booking::factory(10)
            ->afterCreating(function ($booking) {
                $guests = BookingGuest::factory(rand(1, 3))->create([
                    'booking_id' => $booking->id,
                ]);
                $payments = Payment::factory()
                    ->count(rand(1, 2))
                    ->create([
                        'booking_id' => $booking->id,
                    ]);

                $guests = collect($guests)
                    ->map(function (BookingGuest $guest) {
                        return [
                            'name' => $guest->name,
                            'pricing' => $guest->package_pricing_id,
                            'price' => $guest->packagePricing->price,
                        ];
                    })
                    ->toArray();

                foreach ($payments as $payment) {
                    app(GenerateInvoiceAction::class)->execute($payment, [
                        'guests' => $guests,
                    ]);
                    if ($payment->payment_type == 'full'
                        && ($payment->payment_method == 'cash'
                            || $payment->payment_method == 'card'
                            || ($payment->payment_method == 'stripe' && $payment->status == 'paid')
                        )
                    ) {
                        app(GenerateReceiptAction::class)->execute($payment, [
                            'guests' => $guests,
                        ]);
                    }
                }
            })
            ->create();
    }
}
