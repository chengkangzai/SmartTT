<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingGuest;
use App\Models\PackagePricing;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use function rand;

class BookingGuestFactory extends Factory
{
    protected $model = BookingGuest::class;

    #[ArrayShape(['name' => "string", 'package_pricing_id' => "int|mixed", 'booking_id' => "int|mixed", 'is_child' => "int"])]
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'package_pricing_id' => PackagePricing::inRandomOrder()->first()->id,
            'booking_id' => Booking::inRandomOrder()->first()->id,
            'is_child' => rand(0, 1)
        ];
    }
}
