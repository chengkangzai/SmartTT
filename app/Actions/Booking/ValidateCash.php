<?php

namespace App\Actions\Booking;

use App\Models\Payment;
use Illuminate\Validation\ValidationException;
use Validator;

trait ValidateCash
{
    public function validateCash(array $data): array
    {
        if (! isset($data['paymentCashReceived'])) {
            throw ValidationException::withMessages([
                'paymentCashReceived' => [
                    __('Please confirm that you have received the cash.'),
                ],
            ]);
        }

        return Validator::make($data, [
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|in:'.implode(',', Payment::TYPES),
            'billing_name' => 'required|string|max:255',
            'billing_phone' => 'required|string|max:255',
        ])->validate();
    }
}
