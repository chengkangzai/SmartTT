<?php

namespace App\Actions\Package;

trait ValidatePackage
{
    public function validate(array $data)
    {
        return \Validator::make($data, [
            'fee' => 'required|integer|min:0',
            'tour_id' => 'required|integer|exists:tours,id',
            'capacity' => 'required|integer|min:1',
            'depart_time' => 'required|date|after:now',
            'flights' => 'required|array|exists:flights,id',
        ])->validate();
    }
}
