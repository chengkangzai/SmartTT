<?php

namespace Database\Seeders;

use App\Actions\Booking\Invoice\GenerateInvoiceAction;
use App\Actions\Booking\Invoice\GenerateReceiptAction;
use App\Models\Booking;
use App\Models\BookingGuest;
use App\Models\Payment;
use App\Models\Settings\BookingSetting;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run()
    {
        Booking::factory(40)
            ->afterCreating(function (Booking $booking) {
                $setting = app(BookingSetting::class);
                $guests = collect($booking->guests)
                    ->each(function (BookingGuest $guest) {
                        $guest->packagePricing()->decrement('available_capacity');
                    })
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
                if (app()->environment() != 'testing') {
                    foreach ($payments as $payment) {
                        app(GenerateInvoiceAction::class)->execute($payment);
                        if (($payment->payment_type == Payment::TYPE_FULL || $payment->payment_type == Payment::TYPE_REMAINING)
                            && ($payment->payment_method == Payment::METHOD_CASH
                                || $payment->payment_method == Payment::METHOD_CARD
                                || ($payment->payment_method == Payment::METHOD_STRIPE && $payment->status == Payment::STATUS_PAID)
                            )
                        ) {
                            app(GenerateReceiptAction::class)->execute($payment);
                        }
                    }
                }
            })
            ->create();
    }
}
