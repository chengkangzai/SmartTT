<?php

namespace App\Actions\Package;

use App\Models\Package;
use Illuminate\Support\Facades\DB;

class StorePackageAction
{
    use ValidatePackage;

    /**
     * @throws \Throwable
     */
    public function execute(array $data)
    {
        $data = $this->validate($data);

        return DB::transaction(function () use ($data) {
            $trip = Package::create([
                ...$data,
                'fee' => $data['fee'] * 100,
                'depart_time' => $data['depart_time'],
            ]);

            $trip->flight()->attach($data['flights']);
        });
    }
}
