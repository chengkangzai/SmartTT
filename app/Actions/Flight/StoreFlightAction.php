<?php

namespace App\Actions\Flight;

use App\Models\Flight;
use Carbon\Carbon;

class StoreFlightAction
{
    use ValidateFlight;

    public function execute(array $data): Flight
    {
        $data = $this->validate($data);

        return Flight::create([
            ...$data,
            'depart_time' => Carbon::parse($data['depart_time']),
            'arrive_time' => Carbon::parse($data['arrive_time']),
            'fee' => $data['fee'] * 100,
        ]);
    }
}
