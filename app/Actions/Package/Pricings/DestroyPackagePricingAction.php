<?php

namespace App\Actions\Package\Pricings;

use App\Models\PackagePricing;

class DestroyPackagePricingAction
{
    //TODO Check there is no booking for this pricing before delete
    public function execute(PackagePricing $packagePricing): ?bool
    {
        return $packagePricing->delete();
    }
}
