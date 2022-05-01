<?php


use App\Actions\Setting\Update\UpdateGeneralSettingAction;
use App\Models\Settings\GeneralSetting;
use Database\Seeders\CountrySeeder;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\seed;
use function PHPUnit\Framework\assertNotEmpty;

beforeEach(function () {
    seed(CountrySeeder::class);
});

it('should update general setting', function () {
    /** @var GeneralSetting $setting */
    $generalSetting = app(GeneralSetting::class);

    $items = $generalSetting->toArray();
    foreach ($items as $key => $item) {
        $s = DB::table('settings')->where('name', $key)->first();
        if ($item instanceof DateTimeZone) {
            expect(json_decode($s->payload))->toBe($item->getName());
        } else {
            expect(json_decode($s->payload))->toBe($item);
        }
    }

    $data = [
        'site_name' => 'Another Name',
        'default_language' => 'ms',
        'default_timezone' => 'Europe/London',
        'default_currency' => 'British Pound',
        'default_currency_symbol' => '£',
        'default_country' => 'United Kingdom',
    ];
    $action = app(UpdateGeneralSettingAction::class);
    $action->execute($data, $generalSetting);

    $items = $generalSetting->toArray();
    foreach ($items as $key => $item) {
        $s = DB::table('settings')->where('name', $key)->first();
        if ($item instanceof DateTimeZone) {
            expect(json_decode($s->payload))->toBe($item->getName());
        } else {
            expect(json_decode($s->payload))->toBe($item);
        }
    }
});

it('should not update setting', function ($name, $data) {
    /** @var GeneralSetting $setting */
    $bookingSetting = app(GeneralSetting::class);
    /** @var UpdateGeneralSettingAction $action */
    $action = app(UpdateGeneralSettingAction::class);

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
    ['site_name', ['', -1, null, 1, 'a' . str_repeat('a', 256)]],
    ['default_language', ['', 'asda', -1, null, 1, 'a' . str_repeat('a', 256)]],
    ['default_timezone', ['', 'asda', -1, null, 1, 'a' . str_repeat('a', 256)]],
    ['default_currency', ['', -1, null, 1, 'a' . str_repeat('a', 256)]],
    ['default_currency_symbol', ['', -1, null, 1, 'a' . str_repeat('a', 256)]],
    ['default_country', ['', -1, null, 1, 'a' . str_repeat('a', 256)]],
]);
