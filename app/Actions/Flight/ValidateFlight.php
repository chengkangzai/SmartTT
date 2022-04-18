<?php

namespace App\Actions\Flight;

use Validator;

trait ValidateFlight
{
    public function validate(array $data): array
    {
        return Validator::make(
            $data,
            [
                'price' => 'required|numeric|min:0',
                'departure_date' => 'required|date|after:today',
                'arrival_date' => 'required|date|after:today',
                'departure_airport_id' => 'required|integer|exists:airports,id',
                'arrival_airport_id' => 'required|integer|exists:airports,id|not_in:' . $data['departure_airport_id'],
                'airline_id' => 'required|integer|exists:airlines,id',
                'class' => 'required|string',
                'type' => 'required|string',
            ],
            [
                'arrival_airport_id.not_in' => 'Arrival airport must not be the same as departure airport',
            ],
            [
                'departure_airport_id' => 'Departure airport',
                'arrival_airport_id' => 'Arrival airport',
                'airline_id' => 'Airline',
            ]
        )->validate();
    }
}
