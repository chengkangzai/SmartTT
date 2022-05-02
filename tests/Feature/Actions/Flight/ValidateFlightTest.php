<?php

use App\Actions\Flight\ValidateFlight;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\assertNotEmpty;

it('should invalidate invalid data', function ($name, $data) {
    $mock = Mockery::mock(ValidateFlight::class);
    $mock->shouldReceive('validate');

    foreach ($data as $item) {
        $testArray[$name] = $item;
        if (!isset($data['departure_airport_id'])) {
            $testArray['departure_airport_id'] = 1;
        }

        try {
            $mock->validate($testArray);
            $this->fail('ValidationException was not thrown');
        } catch (ValidationException $e) {
            assertNotEmpty($e->validator->errors()->get($name));
        }
    }
})->with([
    ['price', ['asda', -1, null]],
    ['departure_date', ['asda', -1, 100, null, now()->subDays(2)->toString()]],
    ['arrival_date', ['asda', -1, 100, null, now()->subDays(2)->toString()]],
    ['departure_airport_id', ['asda', -1, 100, null, 1]],
    ['arrival_airport_id', ['asda', -1, 100, null, 1]],
    ['airline_id', ['asda', -1, 100, null, 1]],
    ['class', [-1, null]],
    ['type', [-1, null]],
]);

it('should invalidate invalid data without departure airport id ', function () {
    $mock = Mockery::mock(ValidateFlight::class);
    $mock->shouldReceive('validate');

    expect(fn() => $mock->validate([]))->toThrow(ValidationException::class);
});
