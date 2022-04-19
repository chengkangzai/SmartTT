<?php

namespace App\Actions\Package;

trait ValidatePackage
{
    public function validate(array $data): array
    {
        return \Validator::make($data, [
            'tour_id' => 'required|integer|exists:tours,id',
            'depart_time' => 'required|date|after:now',
            'flights' => 'required|array|exists:flights,id',
        ])->validate();
    }
}
