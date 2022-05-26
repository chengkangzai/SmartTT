<?php

namespace App\Http\Controllers;

use App\Actions\Setting\Edit\GetViewBagForFlightSettingAction;
use App\Actions\Setting\Edit\GetViewBagForGeneralSettingAction;
use App\Actions\Setting\Edit\GetViewBagForTourSettingAction;
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
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\LaravelSettings\Console\ClearCachedSettingsCommand;

class SettingController extends Controller
{
    public array $settings = [];

    public function __construct()
    {
        $this->settings = [
            'general' => app(GeneralSetting::class),
            'tour' => app(TourSetting::class),
            'package' => app(PackageSetting::class),
            'package_pricing' => app(PackagePricingsSetting::class),
            'flight' => app(FlightSetting::class),
            'booking' => app(BookingSetting::class),
        ];
    }

    public function index()
    {
        abort_unless(auth()->user()->can('View Setting'), 403);

        return view('smartTT.setting.index', [
            'settings' => $this->settings,
        ]);
    }

    public function edit(string $mode)
    {
        abort_unless(auth()->user()->can('Update Setting'), 403);
        $setting = $this->settings[$mode];
        abort_unless($setting, 404);

        $view = match ($mode) {
            'general' => 'smartTT.setting.general',
            'tour' => 'smartTT.setting.tour',
            'package' => 'smartTT.setting.package',
            'package_pricing' => 'smartTT.setting.package_pricing',
            'flight' => 'smartTT.setting.flight',
            'booking' => 'smartTT.setting.booking',
        };

        $viewBag = match ($mode) {
            'general' => app(GetViewBagForGeneralSettingAction::class),
            'tour' => app(GetViewBagForTourSettingAction::class),
            'flight' => app(GetViewBagForFlightSettingAction::class),
            default => null,
        };

        $viewBag = $viewBag?->execute();

        return view($view, compact('setting', 'viewBag'));
    }

    public function update(Request $request, string $mode)
    {
        abort_unless(auth()->user()->can('Update Setting'), 403);
        $setting = $this->settings[$mode];
        abort_unless($setting, 404);

        /** @var UpdateSettingInterface $action */
        $action = match ($mode) {
            'general' => app(UpdateGeneralSettingAction::class),
            'tour' => app(UpdateTourSettingAction::class),
            'package' => app(UpdatePackageSettingAction::class),
            'package_pricing' => app(UpdatePackagePricingSettingAction::class),
            'flight' => app(UpdateFlightSettingAction::class),
            'booking' => app(UpdateBookingSettingAction::class),
        };

        try {
            $action->execute($request->all(), $setting);
            Artisan::call(ClearCachedSettingsCommand::class);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('settings.index')->with('success', __('Setting updated successfully'));
    }
}
