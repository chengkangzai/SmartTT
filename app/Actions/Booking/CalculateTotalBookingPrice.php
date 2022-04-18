<?php

namespace App\Actions\Booking;

use App\Models\Package;

trait CalculateTotalBookingPrice
{
    use ValidateBooking;

    public function calculate($data): float|int
    {
        $data = $this->validate($data);
        $packagePrice = Package::whereId($data['trip_id'])->firstOrFail()->price;

        return $packagePrice * $data['adult'] + (200 * $data['child']) - $data['adult'];
    }
}
