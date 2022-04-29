<?php

namespace App\Actions\Booking;

use App\Models\Package;
use App\Models\Settings\BookingSetting;

trait CalculateTotalBookingPrice
{
    public function __construct(
        private readonly BookingSetting $bookingSetting,
    ){}

    public function calculate($data): float|int
    {
        $packagePrice = Package::findOrFail($data['package_id'])->price;

        return $packagePrice * $data['adult'] + ($this->bookingSetting->charge_per_child * $data['child']) - $data['discount'];
    }
}
