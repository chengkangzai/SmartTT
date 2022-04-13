<?php

namespace App\Actions\Booking;

use App\Models\Booking;

class StoreActionValidateBookingAction
{
    use ValidateBooking;
    use CalculateTripPrice;

    public function execute(array $data): Booking
    {
        $data = $this->validate($data);

        $price = $this->calculate($data);

        return Booking::create([
            ...$data,
            'total_fee' => $price,
        ]);
    }
}
