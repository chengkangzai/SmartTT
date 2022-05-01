<?php


use App\Actions\Setting\Update\UpdateFlightSettingAction;
use App\Actions\Setting\Update\UpdateTourSettingAction;
use App\Models\Settings\FlightSetting;
use App\Models\Settings\TourSetting;
use Database\Seeders\CountrySeeder;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\seed;
use function PHPUnit\Framework\assertNotEmpty;

beforeEach(function () {
    seed(CountrySeeder::class);
});

it('should update Tour setting', function () {
    /** @var TourSetting $setting */
    $tourSetting = app(TourSetting::class);

    $items = $tourSetting->toArray();
    foreach ($items as $key => $item) {
        assertDatabaseHas('settings', [
            'name' => $key,
            'payload' => json_encode($item),
            'group' => 'tour',
        ]);
    }

    $data = [
        'default_status' => false,
        'default_night' => 1200,
        'default_day' => 12,
        'category' => ['Amateur', 'Professional'],
    ];
    $action = app(UpdateTourSettingAction::class);
    $action->execute($data, $tourSetting);

    $items = $tourSetting->toArray();
    foreach ($items as $key => $item) {
        assertDatabaseHas('settings', [
            'name' => $key,
            'payload' => json_encode($item),
            'group' => 'tour',
        ]);
    }
});

it('should not update setting', function ($name, $data) {
    /** @var TourSetting $setting */
    $bookingSetting = app(TourSetting::class);
    /** @var UpdateTourSettingAction $action */
    $action = app(UpdateTourSettingAction::class);

    foreach ($data as $item) {
        $testArray[$name] = $item;
        $testArray['category'] = ['Amateur', 'Professional'];
        try {
            $action->execute($testArray, $bookingSetting);
            $this->fail('ValidationException was not thrown');
        } catch (ValidationException $e) {
            Log::info($name);
            Log::info($testArray);
            Log::info($e->validator->errors()->get($name));
            assertNotEmpty($e->validator->errors()->get($name));
        }
    }
})->with([
    ['default_status', ['', 'asda', -1, null, 'a' . str_repeat('a', 256)]],
    ['default_night', ['', 'asda', -1, null, 'a' . str_repeat('a', 256)]],
    ['default_day', ['', 'asda', -1, null, 'a' . str_repeat('a', 256)]],
]);
