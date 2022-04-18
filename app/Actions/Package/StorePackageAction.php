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
            $package = Package::create([
                ...$data,
            ]);

            $package->flight()->attach($data['flights']);
        });
    }
}
