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
            ]);

            $package->flight()->sync($data['flights']);

            return $package->refresh();
        });
    }
}
