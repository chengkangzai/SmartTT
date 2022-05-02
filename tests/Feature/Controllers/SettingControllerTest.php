<?php

use App\Actions\Setting\Update\UpdateBookingSettingAction;
use App\Actions\Setting\Update\UpdateFlightSettingAction;
use App\Actions\Setting\Update\UpdateGeneralSettingAction;
use App\Actions\Setting\Update\UpdatePackagePricingSettingAction;
use App\Actions\Setting\Update\UpdatePackageSettingAction;
use App\Actions\Setting\Update\UpdateSettingInterface;
use App\Actions\Setting\Update\UpdateTourSettingAction;
use App\Models\Settings\BookingSetting;
use App\Models\Settings\FlightSetting;
use App\Models\Settings\GeneralSetting;
use App\Models\Settings\PackagePricingsSetting;
use App\Models\Settings\PackageSetting;
use App\Models\Settings\TourSetting;
use App\Models\User;
use Database\Seeders\CountrySeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
        CountrySeeder::class
    ]);
    $this->actingAs(User::first());
});

it('should display index view', function () {
    $this->get(route('settings.index'))
        ->assertViewIs('smartTT.setting.index')
        ->assertViewHas('settings', [
            'general' => app(GeneralSetting::class),
            'tour' => app(TourSetting::class),
            'package' => app(PackageSetting::class),
            'package_pricing' => app(PackagePricingsSetting::class),
            'flight' => app(FlightSetting::class),
            'booking' => app(BookingSetting::class),
        ]);
});

it('should return respective view for each setting', function ($mode, $view) {
    $settings = [
        'general' => app(GeneralSetting::class),
        'tour' => app(TourSetting::class),
        'package' => app(PackageSetting::class),
        'package_pricing' => app(PackagePricingsSetting::class),
        'flight' => app(FlightSetting::class),
        'booking' => app(BookingSetting::class),
    ];
    $this->get(route('settings.edit', $mode))
        ->assertViewIs($view)
        ->assertViewHas('setting', $settings[$mode]);
})->with([
    ['general', 'smartTT.setting.general'],
    ['tour', 'smartTT.setting.tour'],
    ['package', 'smartTT.setting.package'],
    ['package_pricing', 'smartTT.setting.package_pricing'],
    ['flight', 'smartTT.setting.flight'],
    ['booking', 'smartTT.setting.booking'],
]);

it('should update setting', function ($mode, $data) {
    $settings = [
        'general' => app(GeneralSetting::class),
        'tour' => app(TourSetting::class),
        'package' => app(PackageSetting::class),
        'package_pricing' => app(PackagePricingsSetting::class),
        'flight' => app(FlightSetting::class),
        'booking' => app(BookingSetting::class),
    ];

    $items = $settings[$mode]->toArray();
    foreach ($items as $key => $item) {
        $s = DB::table('settings')
            ->where('name', $key)
            ->where('group', $mode)
            ->first();
        if ($item instanceof DateTimeZone) {
            expect(json_decode($s->payload))->toBe($item->getName());
        } else {
            expect(json_decode($s->payload))->toBe($item);
        }
    }

    $this->post(route('settings.update', $mode), $data)
        ->assertRedirect(route('settings.index'))
        ->assertSessionHas('success', 'Setting updated successfully');

    $items = $settings[$mode]->toArray();
    foreach ($items as $key => $item) {
        $s = DB::table('settings')
            ->where('name', $key)
            ->where('group', $mode)
            ->first();
        if ($item instanceof DateTimeZone) {
            expect(json_decode($s->payload))->toBe($item->getName());
        } else {
            expect(json_decode($s->payload))->toBe($item);
        }
    }

})->with([
    ['general', [
        'site_name' => 'Another Name',
        'default_language' => 'ms',
        'default_timezone' => 'Europe/London',
        'default_currency' => 'British Pound',
        'default_currency_symbol' => '£',
        'default_country' => 'United Kingdom',
    ]],
    ['tour', [
        'default_status' => false,
        'default_night' => 1200,
        'default_day' => 12,
        'category' => ['Amateur', 'Professional'],
    ]],
    ['package', [
        'default_status' => 0,
    ]],
    ['package_pricing', [
        'default_namings' => ['Bronze', 'Silver', 'Gold'],
        'default_capacity' => [10, 20, 30],
        'default_status' => [1, 1, 1],
    ]],
    ['flight', [
        'supported_class' => ['business', 'economy', 'first',],
        'supported_type' => ['roundtrip', 'oneway', 'multicity',],
        'default_class' => 'business',
        'default_type' => 'roundtrip',
        'supported_countries' => ['Malaysia', 'Singapore'],
    ]],
    ['booking', [
        'default_payment_method' => 'Stripe',
        'charge_per_child' => 400,
    ]],
]);


it('should not update setting bc w/o required param', function ($mode, $data) {
    $settings = [
        'general' => app(GeneralSetting::class),
        'tour' => app(TourSetting::class),
        'package' => app(PackageSetting::class),
        'package_pricing' => app(PackagePricingsSetting::class),
        'flight' => app(FlightSetting::class),
        'booking' => app(BookingSetting::class),
    ];

    $items = $settings[$mode]->toArray();
    foreach ($items as $key => $item) {
        $s = DB::table('settings')
            ->where('name', $key)
            ->where('group', $mode)
            ->first();
        if ($item instanceof DateTimeZone) {
            expect(json_decode($s->payload))->toBe($item->getName());
        } else {
            expect(json_decode($s->payload))->toBe($item);
        }
    }

    $this->from(route('settings.index'))
        ->post(route('settings.update', $mode), $data)
        ->assertRedirect(route('settings.index'))
        ->assertSessionHasErrors();

})->with([
    ['general',[null]],
    ['tour', [null]],
    ['package', [null]],
    ['package_pricing',[null]],
    ['flight', [null]],
    ['booking', [null]],
]);
