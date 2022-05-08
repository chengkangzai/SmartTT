<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    #[ArrayShape(['paid_at' => "\DateTime", 'status' => "mixed", 'amount' => "float", 'payment_method' => "mixed", 'payment_type' => "mixed", 'user_id' => "int|mixed", 'booking_id' => "int|mixed"])]
    public function definition(): array
    {
        return [
            'paid_at' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'status' => $this->faker->randomElement([Payment::STATUS_PENDING, Payment::STATUS_FAILED, Payment::STATUS_PAID]),
            'amount' => $this->faker->randomFloat(2, 0, 100),
            'payment_method' => $this->faker->randomElement(['cash', 'stripe', 'card']),
            'payment_type' => $this->faker->randomElement(['full', 'reservation']),
            'user_id' => User::inRandomOrder()->first()->id,
            'booking_id' => Booking::inRandomOrder()->first()->id,
        ];
    }
}
