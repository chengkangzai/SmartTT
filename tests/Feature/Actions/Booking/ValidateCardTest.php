<?php

use App\Actions\Booking\ValidateCard;
use App\Models\Payment;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertNotEmpty;

it('should validate valid data', function () {
    $action = Mockery::mock(ValidateCard::class);
    $action->shouldReceive('validateCard');

    try {
        $action->validateCard([
            'card_holder_name' => 'John Doe',
            'card_number' => '1234567890123456',
            'card_expiry_date' => '12/25',
            'card_cvc' => '123',
            'amount' => 100,
            'payment_type' => Payment::TYPE_RESERVATION,
            'billing_name' => 'John Doe',
            'billing_phone' => '123456789',
        ]);
    } catch (ValidationException $exception) {
        assertEmpty($exception);
    }
});

it('should invalidate card that are expired', function () {
    $action = Mockery::mock(ValidateCard::class);
    $action->shouldReceive('validateCard');

    try {
        $action->validateCard([
            'card_holder_name' => 'John Doe',
            'card_number' => '1234567890123456',
            'card_expiry_date' => '12/20',
            'card_cvc' => '123',
            'amount' => 100,
            'payment_type' => Payment::TYPE_RESERVATION,
            'billing_name' => 'John Doe',
            'billing_phone' => '123456789',
        ]);
    } catch (ValidationException $exception) {
        expect($exception->validator->errors()->get('card_expiry_date'))->toBeArray()
            ->and($exception->validator->errors()->get('card_expiry_date'))->toContain(__('Card expiry date must be after next month'));
    }
});

it('should invalidate invalid data', function ($name, $data) {
    $mock = Mockery::mock(ValidateCard::class);
    $mock->shouldReceive('validateCard');
    foreach ($data as $item) {
        $testArray[$name] = $item;

        try {
            $mock->validateCard($testArray);
            $this->fail('ValidationException was not thrown');
        } catch (ValidationException|Throwable $e) {
            assertNotEmpty($e->validator->errors()->get($name));
        }
    }
})->with([
    ['card_holder_name', ['', -1, null, 'a'.str_repeat('a', 255)]],
    ['card_number', ['', -1, 100, null, 'a'.str_repeat('a', 255), '1234', '123456781234567']],
    ['card_expiry_date', ['asda', -1, 100, null, 1111, 1122, 'a'.str_repeat('a', 255)]],
    ['card_cvc', ['asda', -1, 100, null, 1, '12345', 'a'.str_repeat('a', 255)]],
    ['amount', [null]],
    ['payment_type', [null]],
    ['billing_name', [null]],
    ['billing_phone', [null]],
]);
