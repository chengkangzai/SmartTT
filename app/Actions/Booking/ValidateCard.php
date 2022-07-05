<?php

namespace App\Actions\Booking;

use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Validator;

trait ValidateCard
{
    public function validateCard(array $data): array
    {
        $data = Validator::make($data, [
            'card_holder_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z ]+$/'],
            'card_number' => ['required', 'string', 'max:255', 'regex:/^[0-9]{16}$/'],
            'card_expiry_date' => ['required', 'string', 'max:255', 'regex:/^[0-9]{2}\/[0-9]{2}$/'], // MM/YY
            'card_cvc' => ['required', 'string', 'max:255', 'regex:/^[0-9]{3,4}$/'],
            'amount' => 'required',
            'payment_type' => 'required',
            'billing_name' => 'required',
            'billing_phone' => 'required',
        ], customAttributes: [
            'card_holder_name' => __('Card Holder Name'),
            'card_number' => __('Card Number'),
            'card_expiry_date' => __('Card Expiry'),
            'card_cvc' => __('Card CVC'),
        ])->validate();

        $isBeforeNextMonth = Carbon::createFromFormat('m/y', $data['card_expiry_date'])->isBefore(Carbon::now());

        if ($isBeforeNextMonth) {
            throw ValidationException::withMessages([
                'card_expiry_date' => [
                    __('Card expiry date must be after next month'),
                ],
            ]);
        }

        return $data;
    }
}
