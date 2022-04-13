<?php

namespace App\Actions\Flight;

use Validator;

trait ValidateFlight
{
    public function validate(array $data): array
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'departure_date' => 'required|date|after:today',
            'arrival_date' => 'required|date|after:departure_date',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id',
        ])->validate();
    }
}
