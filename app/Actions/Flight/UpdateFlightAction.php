<?php

namespace App\Actions\Flight;

use App\Models\Flight;

class UpdateFlightAction
{
    use ValidateFlight;

    public function execute(array $data, Flight $flight): Flight
    {
        $data = $this->validate($data);

        $flight->update([
            ...$data,
        ]);

        return $flight->refresh();
    }
}
