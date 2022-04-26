<?php

use App\Actions\Tour\DestroyTourAction;
use App\Models\Package;
use App\Models\Tour;
use Database\Seeders\CountrySeeder;
use Database\Seeders\TourSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(TourSeeder::class);
});

it('can destroy a tour', function () {
    $tour = Tour::first();
    assertModelExists($tour);

    $tour->description()->each(function ($description) {
        assertModelExists($description);
    });

    $tour->countries()->each(function ($country) {
        assertModelExists($country);
    });

    app(DestroyTourAction::class)->execute($tour);

    expect($tour->description()->count())->toBe(0);
    $tour->description()->each(function ($description) {
        assertSoftDeleted($description);
    });
    assertSoftDeleted($tour);
});

it('cannot destroy tour that have package', function () {
    $tour = Tour::first();
    assertModelExists($tour);

    Package::factory()->create([
        'tour_id' => $tour->id,
    ]);

    $tour->packages()->each(function ($package) {
        assertModelExists($package);
    });

    expect(fn () => app(DestroyTourAction::class)->execute($tour))
        ->toThrow('This tour has package, you can not delete it, please delete the package first');
});
