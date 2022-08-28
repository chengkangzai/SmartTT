<?php

use App\Actions\Tour\TourDescription\AttachDescriptionToTourAction;
use App\Models\Tour;
use App\Models\TourDescription;
use Database\Seeders\CountrySeeder;
use Database\Seeders\TourSeeder;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;
use function PHPUnit\Framework\assertNotEmpty;

beforeEach(function () {
    seed(CountrySeeder::class);
    seed(TourSeeder::class);
});

it('should attach tour description to tour', function () {
    $tour = Tour::factory()->create();
    assertModelExists($tour);
    assert($tour->description()->count() === 0);

    $action = app(AttachDescriptionToTourAction::class);

    $data = TourDescription::factory()->make()->toArray();
    $dec = $action->execute($data, $tour);

    assertModelExists($dec);
    expect($dec->tour_id)->toBe($tour->id)
        ->and($dec->description)->toBe($data['description'])
        ->and($dec->place)->toBe($data['place']);
    assert($tour->description()->count() === 1);
});

it('should not attach tour description as invalid data', function ($name, $data) {
    $tour = Tour::factory()->create();
    assertModelExists($tour);
    assert($tour->description()->count() === 0);

    foreach ($data as $item) {
        $testArray[$name] = $item;

        try {
            app(AttachDescriptionToTourAction::class)->execute($testArray, $tour);
            $this->fail('ValidationException was not thrown');
        } catch (ValidationException $e) {
            assertNotEmpty($e->validator->errors()->get($name));
        }
    }
})->with([
    ['place', ['', -1, null, 1, 'a' . str_repeat('a', 256)]],
    ['description', [-1, 100, null, 'a' . str_repeat('a', 256)]],
]);
;
