<?php

namespace App\Actions\Booking;

trait ValidateBooking
{
    public function validate($data): array
    {
        $validator = \Validator::make($data, [
            'trip_id' => 'required|integer|exists:trips,id',
            'adult' => 'required|integer|min:1',
            'child' => 'nullable|integer|min:0',
            'user_id' => 'required|integer|exists:users,id',
            'discount' => 'nullable|integer|min:1',
        ]);

        return $validator->validate();
    }
}
