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
    public function execute(array $data, Package $package): Package
    {
        $data = $this->validate($data);

        return DB::transaction(function () use ($data, $package) {
            $package->update([
                ...$data,
                'fee' => $data['fee'] * 100,
                'depart_time' => $data['depart_time'],
            ]);

            $package->flight()->sync($data['flights']);

            return $package->refresh();
        });
    }
}
