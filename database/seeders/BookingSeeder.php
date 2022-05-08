<?php

namespace Database\Seeders;

use App\Actions\Booking\Invoice\GenerateInvoiceAction;
use App\Actions\Booking\Invoice\GenerateReceiptAction;
use App\Models\Booking;
use App\Models\BookingGuest;
use App\Models\Payment;
use App\Models\Settings\BookingSetting;
use Illuminate\Database\Seeder;
use Log;
use function app;
use function collect;
use function dd;

class BookingSeeder extends Seeder
{
    public function run()
    {
        Booking::factory(10)
            ->afterCreating(function (Booking $booking) {
                $setting = app(BookingSetting::class);
                $guests = collect($booking->guests)
                    ->map(function (BookingGuest $guest) use ($setting) {
                        return [
                            'name' => $guest->name,
                            'pricing' => $guest->package_pricing_id,
                            'price' => $guest->packagePricing->price ?? $setting->charge_per_child,
                        ];
                    });

                $booking->total_price = $guests->sum('price');
                $booking->save();

                $payments = Payment::factory()
                    ->count(rand(1, 2))
                    ->afterCreating(function (Payment $payment) {
                        if ($payment->status == Payment::STATUS_PAID && $payment->payment_type == Payment::TYPE_FULL) {
                            $payment->amount = $payment->booking->total_price;
                            $payment->save();
                        }
                    })
                    ->create([
                        'booking_id' => $booking->id,
                    ]);

                foreach ($payments as $payment) {
                    app(GenerateInvoiceAction::class)->execute($payment, [
                        'guests' => $guests->toArray(),
                    ]);
                    if ($payment->payment_type == 'full'
                        && ($payment->payment_method == 'cash'
                            || $payment->payment_method == 'card'
                            || ($payment->payment_method == 'stripe' && $payment->status == 'paid')
                        )
                    ) {
                        app(GenerateReceiptAction::class)->execute($payment, [
                            'guests' => $guests->toArray(),
                        ]);
                    }
                }
            })
            ->create();
    }
}
