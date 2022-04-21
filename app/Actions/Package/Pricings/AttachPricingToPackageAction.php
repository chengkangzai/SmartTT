<?php

namespace App\Actions\Package\Pricings;

use App\Models\Package;
use App\Models\PackagePricing;

class AttachPricingToPackageAction
{
    use ValidatePackagePricing;

    public function execute(array $data, Package $package): PackagePricing
    {
        $data = $this->validate($data);

        return $package->pricings()->create([
            ...$data,
            'available_capacity' => $data['total_capacity'],
        ]);
    }
}
