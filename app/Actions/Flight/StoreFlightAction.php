<?php

namespace App\Actions\Flight;

use App\Models\Flight;

class StoreFlightAction
{
    use ValidateFlight;

    public function execute(array $data): Flight
    {
        $data = $this->validate($data);

        return Flight::create([
            ...$data,
        ]);
    }
}
