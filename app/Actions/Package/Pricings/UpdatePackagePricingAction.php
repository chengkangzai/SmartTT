<?php

namespace App\Actions\Package\Pricings;

use App\Models\PackagePricing;

class UpdatePackagePricingAction
{
    use ValidatePackagePricing;

    public function execute(array $data, PackagePricing $packagePricing): PackagePricing
    {
        $data = $this->validate($data);

        $packagePricing->update([
            ...$data,
            'available_capacity' => $data['total_capacity'],
        ]);

        return $packagePricing->refresh();
    }
}
