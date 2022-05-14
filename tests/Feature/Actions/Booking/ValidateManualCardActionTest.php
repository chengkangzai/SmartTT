<?php

use App\Actions\Booking\ValidateManualCardAction;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertTrue;

it('should validate valid data', function ($field, $value) {
    try {
        app(ValidateManualCardAction::class)->execute($field, $value);
        assertTrue(true);
    } catch (ValidationException $e) {
        $this->fail($e->getMessage());
    }
})->with([
    ['cardHolderName', 'John Doe'],
    ['cardNumber', '1234567890123456'],
    ['cardExpiry', '12/25'],
    ['cardCvc', '123'],
]);

it('should invalidate invalid data', function ($name, $data) {
    $action = app(ValidateManualCardAction::class);
    foreach ($data as $item) {
        try {
            $action->execute($name, $item);
            $this->fail('ValidationException was not thrown');
        } catch (ValidationException $e) {
            assertNotEmpty($e->validator->errors()->get($name));
        }
    }
})->with([
    ['cardHolderName', ['', 1, -1, 'a' . str_repeat('a', 255)]],
    ['cardNumber', ['', 'asdas', 1, -1, 'a' . str_repeat('a', 255)]],
    ['cardExpiry', ['', 'asdas', 1, -1, 'a' . str_repeat('a', 255)]],
    ['cardCvc', ['', 'asdas', 1, -1, 'a' . str_repeat('a', 255)]],
]);

it('should invalidate card that are almost expired', function () {
    $action = app(ValidateManualCardAction::class);

    try {
        $action->execute('cardExpiry', now()->subDays()->format('m/y'));
        $this->fail('ValidationException was not thrown');
    } catch (ValidationException $e) {
        assertNotEmpty($e->validator->errors()->get('cardExpiry'));
    }
});
