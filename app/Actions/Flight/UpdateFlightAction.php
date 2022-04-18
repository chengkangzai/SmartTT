<?php

namespace App\Actions\Flight;

use App\Models\Flight;

class UpdateFlightAction
{
    use ValidateFlight;

    public function execute(array $data, Flight $flight): bool
    {
        $data = $this->validate($data);

        return $flight->update([
            ...$data,
        ]);
    }
}
