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
    public function execute(array $data): Package
    {
        $data = $this->validate($data, isStore: true);

        return DB::transaction(function () use ($data) {
            $package = Package::create([
                ...$data,
            ]);

            $name = $data['name'];
            $price = $data['price'];
            $totalCapacity = $data['total_capacity'];

            for ($i = 1; $i < count($name) + 1; $i++) {
                $package->pricings()->create([
                    'name' => $name[$i],
                    'price' => $price[$i],
                    'total_capacity' => $totalCapacity[$i],
                    'available_capacity' => $totalCapacity[$i],
                    'is_active' => $data['pricing_is_active_' . $i],
                ]);
            }

            $package->flight()->attach($data['flights']);

            return $package->refresh();
        });
    }
}
