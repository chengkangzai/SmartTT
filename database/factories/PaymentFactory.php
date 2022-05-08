<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Settings\BookingSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use function app;
use function rand;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    #[ArrayShape(['paid_at' => "\DateTime", 'status' => "mixed", 'amount' => "float", 'payment_method' => "mixed", 'payment_type' => "mixed", 'user_id' => "int|mixed", 'booking_id' => "int|mixed"])]
    public function definition(): array
    {
        $paymentMethod = $this->faker->randomElement(Payment::METHODS);
        $paymentType = $this->faker->randomElement(Payment::TYPES);
        $booking = Booking::inRandomOrder()->first();
        $reservation_charge_per_pax = app(BookingSetting::class)->reservation_charge_per_pax;
        return [
            'paid_at' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'status' => $paymentMethod == Payment::METHOD_STRIPE
                ? $this->faker->randomElement(Payment::STATUSES)
                : Payment::STATUS_PAID,
            'amount' => $paymentType == Payment::TYPE_FULL
                ? $booking->total_price
                : $booking->guests()->count() * $reservation_charge_per_pax,
            'payment_method' => $paymentMethod,
            'payment_type' => $paymentType,
            'user_id' => User::inRandomOrder()->first()->id,
            'booking_id' => $booking->id,
        ];
    }
}
