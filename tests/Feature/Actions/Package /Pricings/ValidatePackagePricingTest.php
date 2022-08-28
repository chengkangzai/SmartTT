<?php

use App\Actions\Package\Pricings\ValidatePackagePricing;
use Faker\Factory;
use Illuminate\Validation\ValidationException;

use function PHPUnit\Framework\assertNotEmpty;

$faker = Factory::create();

it('should invalidate invalid data', function ($name, $data) {
    $mock = Mockery::mock(ValidatePackagePricing::class);
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
    ['name', ['', -1, null, $faker->sentence(256)]],
    ['price', ['', -1, null, 0]],
    ['total_capacity', ['', -1, null, 0]],
    ['is_active', ['', -1]],
]);
