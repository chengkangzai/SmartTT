<?php

namespace App\Actions\Setting\Update;

use App\Models\Settings\BookingSetting;
use function implode;
use Validator;

class UpdateBookingSettingAction implements UpdateSettingInterface
{
    private BookingSetting $bookingSetting;

    /**
     * @param array $data
     * @param BookingSetting $setting
     * @return BookingSetting
     */
    public function execute(array $data, mixed $setting): BookingSetting
    {
        $this->bookingSetting = $setting;

        $data = $this->validate($data);

        $this->bookingSetting->fill($data);

        return $this->bookingSetting->save();
    }

    public function validate(array $data): ?array
    {
        $validPaymentMethods = implode(',', $this->bookingSetting->supported_payment_method);

        return Validator::make($data, [
            'default_payment_method' => 'required|string|in:' . $validPaymentMethods,
            'charge_per_child' => 'required|numeric|min:0',
            'reservation_charge_per_pax' => 'required|numeric|min:0',
        ], customAttributes: [
            'default_payment_method' => __('Default Payment Method'),
            'charge_per_child' => __('Charge Per Child'),
            'reservation_charge_per_pax' => __('Reservation Charge Per Pax'),
        ])->validate();
    }
}
