<?php

use App\Actions\Tour\StoreTourAction;
use App\Models\Country;
use App\Models\Tour;
use App\Models\TourDescription;
use Database\Seeders\CountrySeeder;
use Database\Seeders\TourSeeder;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(TourSeeder::class);
});

$faker = Faker\Factory::create();

it('should store a tour', function () use ($faker) {
    $mockTour = Tour::factory()->withItineraryAndThumbnailBinary()->make();
    $mockTour['des'] = [1 => $faker->word, $faker->word, $faker->word];
    $mockTour['place'] = [1 => $faker->word, $faker->word, $faker->word];
    $mockTour['countries'] = Country::inRandomOrder()->take(3)->pluck('id')->toArray();

    $tour = app(StoreTourAction::class)->execute($mockTour->toArray());

    expect($tour)->toBeInstanceOf(Tour::class);
    assertModelExists($tour);
    expect($tour->name)->toBe($mockTour->name);
    expect($tour->tour_code)->toBe($mockTour->tour_code);
    expect($tour->days)->toBe($mockTour->days);
    expect($tour->nights)->toBe($mockTour->nights);

    expect($tour->getFirstMedia('thumbnail')->exists())->toBe(true);
    expect($tour->getFirstMedia('itinerary')->exists())->toBe(true);

    $tour->description()->each(function (TourDescription $description, $index) use ($mockTour) {
        expect($description)->toBeInstanceOf(TourDescription::class);
        expect($description->description)->toBe($mockTour['des'][$index + 1]);
        expect($description->place)->toBe($mockTour['place'][$index + 1]);
    });

    expect($tour->countries()->count())->toBe(3);
    $tour->countries()->each(function (Country $country) use ($mockTour) {
        expect($country)->toBeInstanceOf(Country::class);
        expect($country->id)->toBeIn($mockTour['countries']);
    });
});

it('should not store a tour bc w/o des', function () use ($faker) {
    $mockTour = Tour::factory()->withItineraryAndThumbnailBinary()->make();
    $mockTour['place'] = [1 => $faker->word, $faker->word, $faker->word];
    $mockTour['countries'] = Country::inRandomOrder()->take(3)->pluck('id')->toArray();

    try {
        $tour = app(StoreTourAction::class)->execute($mockTour->toArray());
        assertModelMissing($tour);
        expect($tour)->toBeInstanceOf(Tour::class);
    } catch (ValidationException $e) {
        expect($e)->toBeInstanceOf(ValidationException::class);
        expect($e->errors())->toHaveCount(1);
        expect($e->errors()['des'])->toBe([0 => 'The description field is required.']);
    } catch (Exception|Throwable $e) {
        $this->fail('Unexpected exception thrown: ' . $e->getMessage());
    }
});

it('should not store a tour bc w/o place', function () use ($faker) {
    $mockTour = Tour::factory()->withItineraryAndThumbnailBinary()->make();
    $mockTour['des'] = [1 => $faker->word, $faker->word, $faker->word];
    $mockTour['countries'] = Country::inRandomOrder()->take(3)->pluck('id')->toArray();

    try {
        $tour = app(StoreTourAction::class)->execute($mockTour->toArray());
        assertModelMissing($tour);
        expect($tour)->toBeInstanceOf(Tour::class);
    } catch (ValidationException $e) {
        expect($e)->toBeInstanceOf(ValidationException::class);
        expect($e->errors())->toHaveCount(1);
        expect($e->errors()['place'])->toBe([0 => 'The place field is required.']);
    } catch (Exception|Throwable $e) {
        $this->fail('Unexpected exception thrown: ' . $e->getMessage());
    }
});

it('should not store a tour bc w/o countries', function () use ($faker) {
    $mockTour = Tour::factory()->withItineraryAndThumbnailBinary()->make();
    $mockTour['des'] = [1 => $faker->word, $faker->word, $faker->word];
    $mockTour['place'] = [1 => $faker->word, $faker->word, $faker->word];

    try {
        $tour = app(StoreTourAction::class)->execute($mockTour->toArray());
        assertModelMissing($tour);
        expect($tour)->toBeInstanceOf(Tour::class);
    } catch (ValidationException $e) {
        expect($e)->toBeInstanceOf(ValidationException::class);
        expect($e->errors())->toHaveCount(1);
        expect($e->errors()['countries'])->toBe([0 => 'The countries field is required.']);
    } catch (Exception|Throwable $e) {
        $this->fail('Unexpected exception thrown: ' . $e->getMessage());
    }
});
