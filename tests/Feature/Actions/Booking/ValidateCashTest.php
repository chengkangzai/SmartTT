<?php

use App\Actions\Booking\ValidateCash;
use App\Models\Payment;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\assertNotEmpty;

it('should validate valid data', function () {
    $action = Mockery::mock(ValidateCash::class);
    $action->shouldReceive('validateCash')->andReturn([]);

    $data = $action->validateCash([
        'amount' => 100,
        'payment_type' => Payment::TYPE_REMAINING,
        'paymentCashReceived' => true,
        'billing_name' => 'John Doe',
        'billing_phone' => '+1 (123) 456-7890',
    ]);

    expect($data)->toBeArray();
});

it('should invalidate invalid data', function ($name, $data) {
    $action = Mockery::mock(ValidateCash::class);
    $action->shouldReceive('validateCash')->andReturn([]);

    foreach ($data as $item) {
        $testArray[$name] = $item;
        $testArray['paymentCashReceived'] = true;

        try {
            $action->validateCash($testArray);
            $this->fail('ValidationException was not thrown');
        } catch (ValidationException $e) {
            assertNotEmpty($e->validator->errors()->get($name));
        }
    }
})->with([
    ['amount', ['asda', -1, null, '', 'a' . str_repeat('a', 256)]],
    ['payment_type', [-1, 100, null, now()->subDays(2)->toString()]],
    ['billing_name', [-1, 100, null, 1, 'a' . str_repeat('a', 256)]],
    ['billing_phone', [-1, 100, null, 1]],
]);


it('should invalidate paymentCashReceived is false', function () {
    $action = Mockery::mock(ValidateCash::class);
    $action->shouldReceive('validateCash')->andReturn([]);

    try {
        $action->validateCash([
            'amount' => 100,
            'payment_type' => Payment::TYPE_REMAINING,
            'billing_name' => 'John Doe',
            'billing_phone' => '+1 (123) 456-7890',
        ]);
        $this->fail('ValidationException was not thrown');
    } catch (ValidationException $e) {
        assertNotEmpty($e->validator->errors()->get('paymentCashReceived'));
    }
});
