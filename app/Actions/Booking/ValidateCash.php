<?php

namespace App\Actions\Booking;

use Illuminate\Validation\ValidationException;
use Validator;

trait ValidateCash
{
    public function validateCash(array $data): array
    {
        if (!isset($data['paymentCashReceived'])) {
            throw ValidationException::withMessages([
                'paymentCashReceived' => [
                    __('Please confirm that you have received the cash.'),
                ],
            ]);
        }
        return Validator::make($data, [
            'amount' => 'required',
            'payment_type' => 'required',
            'billing_name' => 'required',
            'billing_phone' => 'required',
        ])->validate();
    }
}
