<?php

use App\Actions\Tour\TourDescription\AttachDescriptionToTourAction;
use App\Actions\Tour\TourDescription\UpdateTourDescriptionAction;
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

it('should update tour description to tour', function () {
    $tour = Tour::factory()->create();
    assertModelExists($tour);
    assert($tour->description()->count() === 0);

    $td = TourDescription::factory()->create();
    assertModelExists($td);

    $mock = TourDescription::factory()->make();
    $action = app(UpdateTourDescriptionAction::class);
    $dec = $action->execute($mock->toArray(), $td);

    expect($dec)->toBeInstanceOf(TourDescription::class);
    assertModelExists($dec);

});

it('should not attach tour description as invalid data', function ($name, $data) {
    $tour = Tour::factory()->create();
    assertModelExists($tour);
    assert($tour->description()->count() === 0);
    $td = TourDescription::factory()->create();

    foreach ($data as $item) {
        $testArray[$name] = $item;
        try {
            app(UpdateTourDescriptionAction::class)->execute($testArray, $td);
            $this->fail('ValidationException was not thrown');
        } catch (ValidationException $e) {
            assertNotEmpty($e->validator->errors()->get($name));
        }
    }
})->with([
    ['place', ['', -1, null, 1, 'a' . str_repeat('a', 256)]],
    ['description', [-1, 100, null, 'a' . str_repeat('a', 256)]],
    ['order', [-1, 'a' . str_repeat('a', 256)]],
]);
