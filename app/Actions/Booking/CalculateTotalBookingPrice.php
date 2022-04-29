<?php

namespace App\Actions\Booking;

use App\Models\Package;
use App\Models\Settings\BookingSetting;

trait CalculateTotalBookingPrice
{
    public function calculate($data, BookingSetting $bookingSetting): float|int
    {
        $packagePrice = Package::findOrFail($data['package_id'])->price;

        return $packagePrice * $data['adult'] + ($bookingSetting->charge_per_child * $data['child']) - $data['discount'];
    }
}
