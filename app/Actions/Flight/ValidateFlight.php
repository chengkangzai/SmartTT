<?php

namespace App\Actions\Flight;

use Illuminate\Validation\ValidationException;
use Validator;

trait ValidateFlight
{
    public function validate(array $data): array
    {
        if (! isset($data['departure_airport_id'])) {
            throw ValidationException::withMessages([
                'departure_airport_id' => [__('The departure airport field is required.')],
            ]);
        }

        return Validator::make($data, [
            'price' => 'required|numeric|min:0',
            'departure_date' => 'required|date|after:today',
            'arrival_date' => 'required|date|after:today',
            'departure_airport_id' => 'required|integer|exists:airports,id',
            'arrival_airport_id' => 'required|integer|exists:airports,id|not_in:' . $data['departure_airport_id'],
            'airline_id' => 'required|integer|exists:airlines,id',
            'class' => 'required|string',
            'type' => 'required|string',
        ], [
            'arrival_airport_id.not_in' => __('Arrival airport must not be the same as departure airport'),
        ], [
            'departure_airport_id' => __('Departure airport'),
            'arrival_airport_id' => __('Arrival airport'),
            'airline_id' => __('Airline'),
            'class' => __('Class'),
            'type' => __('Type'),
            'price' => __('Price'),
            'departure_date' => __('Departure Date'),
            'arrival_date' => __('Arrival Date'),
        ])->validate();
    }
}
