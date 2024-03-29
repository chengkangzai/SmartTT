<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingGuest;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

use function rand;

class BookingGuestFactory extends Factory
{
    protected $model = BookingGuest::class;

    #[ArrayShape(['name' => 'string', 'package_pricing_id' => 'int|mixed', 'booking_id' => 'int|mixed', 'is_child' => 'int'])]
    public function definition(): array
    {
        $isChild = rand(0, 100) > 90;
        $booking = Booking::inRandomOrder()->first();

        return [
            'name' => $this->faker->name,
            'package_pricing_id' => null,
            'booking_id' => $booking->id,
            'is_child' => $isChild,
        ];
    }
}
