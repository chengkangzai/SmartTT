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
    $oriTour = Tour::factory()->withFakerItineraryAndThumbnail()->create();
    assertModelExists($oriTour);

    $mockTour = Tour::factory()->withItineraryAndThumbnailBinary()->make();
    $mockTour['countries'] = Country::inRandomOrder()->take(3)->pluck('id')->toArray();

    $updatedTour = app(UpdateTourAction::class)->execute($mockTour->toArray(), $oriTour);

    expect($updatedTour)->toBeInstanceOf(Tour::class);
    assertModelExists($updatedTour);
    expect($updatedTour->name)->toBe($mockTour->name)
        ->and($updatedTour->tour_code)->toBe($mockTour->tour_code)
        ->and($updatedTour->days)->toBe($mockTour->days)
        ->and($updatedTour->nights)->toBe($mockTour->nights)
        ->and($updatedTour->countries()->count())->toBe(3);

    $updatedTour->countries()->each(function (Country $country) use ($mockTour) {
        expect($country)->toBeInstanceOf(Country::class)
            ->and($country->id)->toBeIn($mockTour['countries']);
    });
});


it('should not update tour because w/o country', function () {
    $oriTour = Tour::factory()->create();
    assertModelExists($oriTour);

    $mockTour = Tour::factory()->make();
    expect(fn () => app(UpdateTourAction::class)->execute($mockTour->toArray(), $oriTour))
        ->toThrow('The Countries field is required.');
});
