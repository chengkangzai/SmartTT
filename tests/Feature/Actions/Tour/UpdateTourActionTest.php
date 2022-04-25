<?php

use App\Actions\Tour\UpdateTourAction;
use App\Models\Country;
use App\Models\Tour;
use Database\Seeders\CountrySeeder;
use Database\Seeders\TourSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(TourSeeder::class);
});

$faker = Faker\Factory::create();

it('should update tour', function () use ($faker) {
    $oriTour = Tour::factory()->create();
    assertModelExists($oriTour);

    $mockTour = Tour::factory()->make();
    $mockTour['countries'] = Country::inRandomOrder()->take(3)->pluck('id')->toArray();

    $ex = app(UpdateTourAction::class)->execute($mockTour->toArray(), $oriTour);

    expect($ex)->toBeInstanceOf(Tour::class);
    assertModelExists($ex);
    expect($ex->name)->toBe($mockTour->name);
    expect($ex->tour_code)->toBe($mockTour->tour_code);
    expect($ex->days)->toBe($mockTour->days);
    expect($ex->nights)->toBe($mockTour->nights);

    collect($mockTour['countries'])->each(function ($countryId) use ($ex) {
        expect($ex->countries->contains($countryId))->toBe(true);
    });
});


it('should not update tour bc w/o country', function () {
    $oriTour = Tour::factory()->create();
    assertModelExists($oriTour);

    $mockTour = Tour::factory()->make();
    expect(fn () => app(UpdateTourAction::class)->execute($mockTour->toArray(), $oriTour))
        ->toThrow('The countries field is required.');
});
