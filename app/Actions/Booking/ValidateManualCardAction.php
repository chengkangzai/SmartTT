<?php

namespace App\Actions\Booking;

use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Validator;

class ValidateManualCardAction
{
    private array $validateCardRule = [
        'cardHolderName' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z ]+$/'],
        'cardNumber' => ['required', 'string', 'max:255', 'regex:/^[0-9]{16}$/'],
        'cardExpiry' => ['required', 'string', 'max:255', 'regex:/^[0-9]{2}\/[0-9]{2}$/'], // MM/YY
        'cardCvc' => ['required', 'string', 'max:255', 'regex:/^[0-9]{3,4}$/'],
    ];

    public function execute(string $field, string $value): void
    {
        Validator::make(
            [$field => $value],
            [$field => $this->validateCardRule[$field]],
            customAttributes: [
                'cardHolderName' => __('Card Holder Name'),
                'cardNumber' => __('Card Number'),
                'cardExpiry' => __('Card Expiry'),
                'cardCvc' => __('Card CVC'),
            ])->validate();

        if ($field == 'cardExpiry') {
            $isBeforeNextMonth = Carbon::createFromFormat('m/y', $value)->isBefore(Carbon::now());
            if ($isBeforeNextMonth) {
                throw ValidationException::withMessages([$field => __('The card is expired')]);
            }
        }
    }
}
