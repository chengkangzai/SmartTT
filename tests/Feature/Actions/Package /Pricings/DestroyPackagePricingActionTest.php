<?php

use App\Actions\Package\Pricings\DestroyPackagePricingAction;
use App\Models\PackagePricing;
use Database\Seeders\CountrySeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\TourSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(TourSeeder::class);
    seed(PackageSeeder::class);
});

it('should destroy a package pricing', function () {
    $pp = PackagePricing::factory()->create();

    assertModelExists($pp);

    app(DestroyPackagePricingAction::class)->execute($pp);

    assertSoftDeleted($pp);
});
