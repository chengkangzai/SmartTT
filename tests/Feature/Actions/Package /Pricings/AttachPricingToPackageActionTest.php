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

    expect($newPricing)->toBeInstanceOf(PackagePricing::class)
        ->and($newPricing->id)->toBeGreaterThan(0)
        ->and($newPricing->package_id)->toBe($fakePricing->package_id)
        ->and($newPricing->price)->toBe($fakePricing->price)
        ->and($newPricing->name)->toBe($fakePricing->name)
        ->and($newPricing->total_capacity)->toBe($fakePricing->total_capacity)
        ->and($newPricing->available_capacity)->toBe($fakePricing->available_capacity)
        ->and($newPricing->is_active)->toBe($fakePricing->is_active);


    assertModelExists($newPricing);
});
