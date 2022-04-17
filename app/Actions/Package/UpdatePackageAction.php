<?php

namespace App\Actions\Package;

use App\Models\Package;
use Illuminate\Support\Facades\DB;

class UpdatePackageAction
{
    use ValidatePackage;

    /**
     * @throws \Throwable
     */
    public function execute(array $data, Package $trip): Package
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
