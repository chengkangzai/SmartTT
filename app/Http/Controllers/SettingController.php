<?php

namespace App\Http\Controllers;

use App\Models\Settings\BookingSetting;
use App\Models\Settings\FlightSetting;
use App\Models\Settings\GeneralSetting;
use App\Models\Settings\PackagePricingsSetting;
use App\Models\Settings\PackageSetting;
use App\Models\Settings\TourSetting;
use function abort;
use function app;
use function dd;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'tour' => app(TourSetting::class),
            'general' => app(GeneralSetting::class),
            'package' => app(PackageSetting::class),
            'package_pricing' => app(PackagePricingsSetting::class),
            'flight' => app(FlightSetting::class),
            'booking' => app(BookingSetting::class),
        ];

        return view('smartTT.setting.index', compact('settings'));
    }

    public function edit($mode)
    {
        $setting = match ($mode) {
            'tour' => app(TourSetting::class),
            'general' => app(GeneralSetting::class),
            'package' => app(PackageSetting::class),
            'package_pricing' => app(PackagePricingsSetting::class),
            'flight' => app(FlightSetting::class),
            'booking' => app(BookingSetting::class),
            default => abort(404),
        };

        $view = match ($mode) {
            'tour' => 'smartTT.setting.tour',
            'general' => 'smartTT.setting.general',
            'package' => 'smartTT.setting.package',
            'package_pricing' => 'smartTT.setting.package_pricing',
            'flight' => 'smartTT.setting.flight',
            'booking' => 'smartTT.setting.booking',
        };

        return view($view, compact('setting'));
    }

    public function update()
    {

    }

}
