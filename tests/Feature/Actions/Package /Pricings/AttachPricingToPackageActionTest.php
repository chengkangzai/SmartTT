<?php

use App\Actions\Package\Pricings\AttachPricingToPackageAction;
use App\Models\Package;
use App\Models\PackagePricing;
use Database\Seeders\CountrySeeder;
use Database\Seeders\TourSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(TourSeeder::class);
});

it('should attach pricing to to package', function () {
    $package = Package::factory()->create();
    $action = app(AttachPricingToPackageAction::class);

    $fakePricing = PackagePricing::factory()->make();

    $newPricing = $action->execute($fakePricing->toArray(), $package);

    expect($newPricing)->toBeInstanceOf(PackagePricing::class);

    assertModelExists($newPricing);
});
