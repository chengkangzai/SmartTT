<?php

namespace App\Actions\Booking;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidateBookingGuestAction
{
    /**
     * @throws ValidationException
     */
    public function execute(array $pricingHolder, array $data): void
    {
        $data = Validator::make($data, [
            'guests.*.name' => 'required|string|max:255',
            'guests.*.pricing' => 'required|integer',
            'guests.*.price' => 'required|numeric|min:1',
            'guests.*.is_child' => 'required|boolean',
        ], attributes: [
            'guests.*.name' => __('Guest Name'),
            'guests.*.pricing' => __('Pricing'),
            'guests.*.price' => __('Price'),
            'guests.*.is_child' => __('Is Child'),
        ])->validate();

        $isValid = collect($data['guests'])
            ->filter(fn ($guest) => ! $guest['is_child'])
            ->map(function ($guest) use (&$pricingHolder) {
                $arr = array_filter($pricingHolder, function ($p) use ($guest) {
                    return $p['id'] == $guest['pricing'];
                });

                $index = array_keys($arr)[0];
                $pricing = $arr[$index];
                if ($pricing['available_capacity'] < 1) {
                    return [
                        'valid' => false,
                        'pricing' => $pricing['name'],
                    ];
                }

                $pricingHolder[$index]['available_capacity'] -= 1;

                return [
                    'valid' => true,
                ];
            });

        if ($isValid->pluck('valid')->filter(fn ($v) => $v == false)->isNotEmpty()) {
            throw ValidationException::withMessages(['guests' => __('There is not enough capacity for the selected pricing of :packageName', [
                'packageName' => $isValid->pluck('pricing')->filter()->first(),
            ]),
            ]);
        }
    }
}
