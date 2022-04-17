<?php

namespace App\Actions\Booking;

use App\Models\Package;

trait CalculateTotalBookingPrice
{
    use ValidateBooking;

    public function calculate($data): float|int
    {
        $data = $this->validate($data);
        $tripPrice = Package::whereId($data['trip_id'])->firstOrFail()->fee / 100;

        return $tripPrice * $data['adult'] + (200 * $data['child']) - $data['adult'];
    }
}