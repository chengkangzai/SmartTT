<?php

namespace App\Actions\Package;

use App\Models\Flight;
use App\Models\Settings\PackagePricingsSetting;
use App\Models\Settings\PackageSetting;
use App\Models\Tour;
use function app;

class GetTourAndFlightForCreateAndUpdatePackage
{
    public function execute($loadPackageSetting = true, $loadPricingSetting = true): array
    {
        $tours = Tour::select(['id', 'name', 'tour_code'])->get();
        $flights = Flight::with('airline:id,name')
            ->select(['id', 'airline_id', 'departure_date', 'arrival_date'])
            ->where('departure_date', ">=", now())
            ->where('arrival_date', ">=", now())
            ->orderBy('departure_date')
            ->orderBy('arrival_date')
            ->get();

        $packageSetting = $loadPackageSetting ? app(PackageSetting::class) : null;

        $packagePricingSetting = $loadPricingSetting ? app(PackagePricingsSetting::class) : null;

        return [$tours, $flights, $packageSetting, $packagePricingSetting];
    }
}
