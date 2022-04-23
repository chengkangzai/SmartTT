<?php


use App\Actions\Package\ValidatePackage;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\assertNotEmpty;

it('should invalidate invalid data with Update', function ($name, $data) {
    $mock = Mockery::mock(ValidatePackage::class);
    $mock->shouldReceive('validate');

    foreach ($data as $item) {
        $testArray[$name] = $item;
        try {
            $mock->validate($testArray);
        } catch (ValidationException $e) {
            assertNotEmpty($e->validator->errors()->get($name));
        }
    }
})->with([
    ['tour_id', ['', 'asda', -1, null, 1]],
    ['depart_time', ['asda', -1, 100, null]],
    ['flights', ['asda', -1, 100, null, 1]],
    ['is_active', [-1, 'asda', '']],
]);
