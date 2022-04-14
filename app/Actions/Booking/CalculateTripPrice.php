<?php

namespace App\Actions\Booking;

use App\Models\Trip;

trait CalculateTripPrice
{
    use ValidateBooking;

    public function calculate($data): float|int
    {
        $data = $this->validate($data);
        $tripPrice = Trip::whereId($data['trip_id'])->firstOrFail()->fee / 100;

        return $tripPrice * $data['adult'] + (200 * $data['child']) - $data['adult'];
    }
}
