<?php

use App\Actions\Package\Pricings\UpdatePackagePricingAction;
use App\Models\PackagePricing;
use Database\Seeders\CountrySeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\TourSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(TourSeeder::class);
    seed(PackageSeeder::class);
});

it('should update package pricing', function () {
    $pp = PackagePricing::factory()->create();

    assertModelExists($pp);

    $action = app(UpdatePackagePricingAction::class);

    $fakePP = PackagePricing::factory()->make();

    $newPP = $action->execute($fakePP->toArray(), $pp);

    expect($newPP)->toBeInstanceOf(PackagePricing::class);
    assertModelExists($newPP);
});
