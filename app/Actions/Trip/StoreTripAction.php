<?php

namespace App\Actions\Trip;

use App\Models\Trip;
use Illuminate\Support\Facades\DB;

class StoreTripAction
{
    use ValidateTrip;

    /**
     * @throws \Throwable
     */
    public function execute(array $data)
    {
        $data = $this->validate($data);

        return DB::transaction(function () use ($data) {
            $trip = Trip::create([
                ...$data,
                'fee' => $data['fee'] * 100,
                'depart_time' => $data['depart_time'],
            ]);

            $trip->flight()->attach($data['flights']);
        });
    }
}
