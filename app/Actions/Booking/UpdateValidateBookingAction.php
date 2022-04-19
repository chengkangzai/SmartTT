<?php

namespace App\Actions\Booking;

use App\Models\Booking;

class UpdateValidateBookingAction
{
    use CalculateTotalBookingPrice;
    use ValidateBooking;

    public function execute(array $data, Booking $booking): Booking
    {
        $data = $this->validate($data);

        $price = $this->calculate($data);

        $booking->update([
            ...$data,
            'total_price' => $price,
        ]);

        return $booking->refresh();
    }
}
