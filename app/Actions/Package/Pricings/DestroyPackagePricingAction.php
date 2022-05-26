<?php

namespace App\Actions\Package\Pricings;

use App\Models\PackagePricing;

class DestroyPackagePricingAction
{
    /**
     * @throws \Exception
     */
    public function execute(PackagePricing $packagePricing): ?bool
    {
        if ($packagePricing->guests()->count() > 0) {
            throw new \Exception('This pricing is used in a booking. You can not delete it.');
        }
        return $packagePricing->delete();
    }
}
