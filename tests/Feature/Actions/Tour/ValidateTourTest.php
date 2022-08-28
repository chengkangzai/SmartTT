<?php

use App\Actions\Tour\ValidateTour;
use App\Models\Tour;
use Database\Seeders\CountrySeeder;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\seed;
use function PHPUnit\Framework\assertNotEmpty;

beforeEach(function () {
    seed(CountrySeeder::class);
});


it('should invalidate invalid data', function ($name, $data) {
    $mock = Mockery::mock(ValidateTour::class);
    $mock->shouldReceive('validate');

    foreach ($data as $item) {
        $testArray[$name] = $item;

        try {
            $mock->validate($testArray);
            $this->fail('ValidationException was not thrown');
        } catch (ValidationException $e) {
            assertNotEmpty($e->validator->errors()->get($name));
        }
    }
})->with([
    ['name', ['', -1, null, 'a' . str_repeat('a', 255)]],
    ['category', ['', -1, null, 'a' . str_repeat('a', 255)]],
    ['countries', [['asda'], [-1], [null], ['']]],
    ['nights', ['asda', -1, null, '']],
    ['days', ['asda', -1, null, '']],
    ['is_active', ['asda', -1, 100]],
]);


it('should invalidate invalid data w/ update', function ($name, $data) {
    $mock = Mockery::mock(ValidateTour::class);
    $mock->shouldReceive('validate');

    foreach ($data as $item) {
        $testArray[$name] = $item;
        $tour = Tour::factory()->create();

        try {
            $mock->validate($testArray, tour: $tour);
            $this->fail('ValidationException was not thrown');
        } catch (ValidationException $e) {
            assertNotEmpty($e->validator->errors()->get($name));
        }
    }
})->with([
    ['name', ['', -1, null, 'a' . str_repeat('a', 255)]],
    ['category', ['', -1, null, 'a' . str_repeat('a', 255)]],
    ['countries', [['asda'], [-1], [null], ['']]],
    ['nights', ['asda', -1, null, '']],
    ['days', ['asda', -1, null, '']],
    ['is_active', ['asda', -1, 100]],
]);

it('should invalidate invalid data w/ store', function ($name, $data) {
    $mock = Mockery::mock(ValidateTour::class);
    $mock->shouldReceive('validate');

    foreach ($data as $item) {
        $testArray[$name] = $item;

        try {
            $mock->validate($testArray, isStore: true);
            $this->fail('ValidationException was not thrown');
        } catch (ValidationException $e) {
            if ($name === 'des' || $name === 'place') {
                assertNotEmpty($e->validator->errors()->get($name . '.*'));
            } else {
                assertNotEmpty($e->validator->errors()->get($name));
            }
        }
    }
})->with([
    ['name', ['', -1, null, 'a' . str_repeat('a', 255)]],
    ['category', ['', -1, null, 'a' . str_repeat('a', 255)]],
    ['countries', [['asda'], [-1], [null], ['']]],
    ['nights', ['asda', -1, null, '']],
    ['days', ['asda', -1, null, '']],
    ['is_active', ['asda', -1, 100]],
    ['des', [['a' . str_repeat('a', 255)], [-1], [null], ['']]],
    ['place', [['a' . str_repeat('a', 255)], [-1], [null], ['']]],
]);
