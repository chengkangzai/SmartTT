<?php

use App\Actions\Setting\Update\UpdateFlightSettingAction;
use App\Models\Settings\FlightSetting;
use Database\Seeders\CountrySeeder;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\seed;
use function PHPUnit\Framework\assertNotEmpty;

beforeEach(function () {
    seed(CountrySeeder::class);
});

it('should update Flight setting', function () {
    /** @var FlightSetting $setting */
    $flightSetting = app(FlightSetting::class);

    $items = $flightSetting->toArray();
    foreach ($items as $key => $item) {
        $s = DB::table('settings')->where('name', $key)->first();
        expect(json_decode($s->payload))->toBe($item);
    }

    $data = [
        'supported_class' => ['business', 'economy', 'first',],
        'supported_type' => ['roundtrip', 'oneway', 'multicity',],
        'default_class' => 'business',
        'default_type' => 'roundtrip',
        'supported_countries' => ['Malaysia', 'Singapore'],
    ];
    $action = app(UpdateFlightSettingAction::class);
    $action->execute($data, $flightSetting);

    $items = $flightSetting->toArray();
    foreach ($items as $key => $item) {
        $s = DB::table('settings')->where('name', $key)->first();
        expect(json_decode($s->payload))->toBe($item);
    }
});

it('should not update setting', function ($name, $data) {
    /** @var FlightSetting $setting */
    $bookingSetting = app(FlightSetting::class);
    /** @var UpdateFlightSettingAction $action */
    $action = app(UpdateFlightSettingAction::class);

    foreach ($data as $item) {
        $testArray[$name] = $item;
        $testArray['supported_type'] = ['roundtrip', 'oneway', 'multicity',];
        $testArray['supported_class'] = ['business', 'economy', 'first',];

        try {
            $action->execute($testArray, $bookingSetting);
            $this->fail('ValidationException was not thrown');
        } catch (ValidationException $e) {
            assertNotEmpty($e->validator->errors()->get($name));
        }
    }
})->with([
    ['default_class', ['', 'asda', -1, null, 1, 'a' . str_repeat('a', 256)]],
    ['default_type', ['', 'asda', -1, null, 1, 'a' . str_repeat('a', 256)]],
    ['supported_countries', ['asda', -1, null]],
]);
