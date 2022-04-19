<?php

namespace App\Actions\Booking;

use App\Models\Package;

trait CalculateTotalBookingPrice
{
    public function calculate($data): float|int
    {
        $packagePrice = Package::whereId($data['package_id'])->firstOrFail()->price;

        return $packagePrice * $data['adult'] + (200 * $data['child']) - $data['discount'];
    }
}
