<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingGuest;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use function rand;

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
            'discount' => 0,
            'adult' => rand(1, 10),
            'child' => rand(0, 3),
        ];
    }

    public function configure(): BookingFactory
    {
        return $this->afterCreating(function (Booking $booking) {
            $guests = BookingGuest::factory(rand(2, 5))
                ->afterCreating(function (BookingGuest $guest) use ($booking) {
                    $guest->package_pricing_id = $guest->is_child
                        ? null
                        : PackagePricing::wherePackageId($booking->package_id)->inRandomOrder()->first()->id;
                })
                ->create([
                    'booking_id' => $booking->id,
                ]);

            $booking->adult = $guests->where('is_child', false)->count();
            $booking->child = $guests->where('is_child', true)->count();
            $booking->save();
        });
    }
}
