<?php

use App\Actions\Setting\Update\UpdateBookingSettingAction;
use App\Models\Settings\BookingSetting;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\assertNotEmpty;

it('should update booking setting', function () {
    /** @var BookingSetting $setting */
    $bookingSetting = app(BookingSetting::class);
    $items = $bookingSetting->toArray();
    foreach ($items as $key => $item) {
        $s = DB::table('settings')->where('name', $key)->first();
        expect(json_decode($s->payload))->toBe($item);
    }

    $data = [
        'default_payment_method' => 'Stripe',
        'charge_per_child' => 400,
        'reservation_charge_per_pax' => 400,
    ];
    $action = app(UpdateBookingSettingAction::class);
    $action->execute($data, $bookingSetting);

    $items = $bookingSetting->toArray();
    foreach ($items as $key => $item) {
        $s = DB::table('settings')->where('name', $key)->first();
        expect(json_decode($s->payload))->toBe($item);
    }
});

it('should not update setting', function ($name, $data) {
    /** @var BookingSetting $setting */
    $bookingSetting = app(BookingSetting::class);
    /** @var UpdateBookingSettingAction $action */
    $action = app(UpdateBookingSettingAction::class);

    foreach ($data as $item) {
        $testArray[$name] = $item;

        try {
            $action->execute($testArray, $bookingSetting);
            $this->fail('ValidationException was not thrown');
        } catch (ValidationException $e) {
            assertNotEmpty($e->validator->errors()->get($name));
        }
    }
})->with([
    ['default_payment_method', ['', 'asda', -1, null, 1, 'a' . str_repeat('a', 256)]],
    ['charge_per_child', ['asda', -1, null]],
]);
