<?php

use App\Actions\Tour\StoreTourAction;
use App\Models\Country;
use App\Models\Tour;
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
