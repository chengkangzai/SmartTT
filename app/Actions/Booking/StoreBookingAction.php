<?php

namespace App\Actions\Booking;

use App\Models\Booking;
use App\Models\User;

class StoreBookingAction
{
    public function execute(User $user, array $data): Booking
    {
        $booking = Booking::create([
            ...$data,
            'user_id' => $user->id,
            'discount' => 0, // TODO Coupon
        ]);

        collect($data['guests'])
            ->each(function ($guest) use ($booking) {
                $booking->guests()->create([
                    'name' => $guest['name'],
                    'price' => $guest['price'],
                    'is_child' => $guest['is_child'],
                    'package_pricing_id' => $guest['pricing'] ?: null,
                ]);
            });

        return $booking->refresh();
    }
}
