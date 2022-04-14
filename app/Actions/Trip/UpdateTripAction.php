<?php

namespace App\Actions\Trip;

use App\Models\Trip;
use Illuminate\Support\Facades\DB;

class UpdateTripAction
{
    use ValidateTrip;

    /**
     * @throws \Throwable
     */
    public function execute(array $data, Trip $trip): Trip
    {
        $data = $this->validate($data);

        return DB::transaction(function () use ($data, $trip) {
            $trip->update([
                ...$data,
                'fee' => $data['fee'] * 100,
                'depart_time' => $data['depart_time'],
            ]);

            $trip->flight()->attach($data['flights']);

            return $trip->refresh();
        });
    }
}
