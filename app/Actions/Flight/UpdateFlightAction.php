<?php

namespace App\Actions\Flight;

use App\Models\Flight;
use Carbon\Carbon;

class UpdateFlightAction
{
    use ValidateFlight;

    public function execute(array $data, Flight $flight): bool
    {
        $data = $this->validate($data);

        return $flight->update([
            ...$data,
            'depart_time' => Carbon::parse($data['depart_time']),
            'arrive_time' => Carbon::parse($data['arrive_time']),
            'fee' => $data['fee'] * 100,
        ]);
    }
}
