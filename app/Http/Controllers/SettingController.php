<?php

namespace App\Http\Controllers;

use App\Models\Settings\BookingSetting;
use App\Models\Settings\FlightSetting;
use App\Models\Settings\GeneralSetting;
use App\Models\Settings\PackagePricingsSetting;
use App\Models\Settings\PackageSetting;
use App\Models\Settings\TourSetting;
use function app;

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

    public function edit($mode , $key)
    {
        // Get Mode and Value
    }

    public function update()
    {

    }

}
