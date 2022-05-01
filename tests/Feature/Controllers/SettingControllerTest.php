<?php

use App\Models\Settings\BookingSetting;
use App\Models\Settings\FlightSetting;
use App\Models\Settings\GeneralSetting;
use App\Models\Settings\PackagePricingsSetting;
use App\Models\Settings\PackageSetting;
use App\Models\Settings\TourSetting;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class
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

