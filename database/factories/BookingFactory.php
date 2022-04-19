<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    #[ArrayShape(['user_id' => "int|mixed", 'package_id' => "int|mixed", 'total_price' => "int", 'discount' => "int", 'adult' => "int", 'child' => "int"])]
    public function definition(): array
    {
        return [
            'user_id' => User::where('id', '>', 1)->inRandomOrder()->first()->id,
            'package_id' => Package::inRandomOrder()->first()->id,
            'total_price' => rand(100, 1000),
            'discount' => rand(0, 100),
            'adult' => rand(1, 10),
            'child' => rand(0, 3)
        ];
    }
}
