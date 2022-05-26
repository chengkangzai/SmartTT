<?php

namespace App\Http\Controllers;

use App\Actions\Package\GetTourAndFlightForCreateAndUpdatePackage;
use App\Actions\Package\StorePackageAction;
use App\Actions\Package\UpdatePackageAction;
use App\Models\Package;
use App\Models\Settings\GeneralSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\Models\Activity;

class PackageController extends Controller
{
    public function index(): View|Factory|Application
    {
        abort_unless(auth()->user()->can('View Package'), 403);
        $role = auth()->user()->roles()->first()->name;
        $packages = Package::with('tour', 'flight.airline:id,name', 'pricings')
            ->when($role === 'Customer', fn ($q) => $q->active())
            ->orderByDesc('id')
            ->paginate(10);
        $setting = app(GeneralSetting::class);

        return view('smartTT.package.index', compact('packages', 'setting'));
    }

    public function create(GetTourAndFlightForCreateAndUpdatePackage $get): Factory|View|Application
    {
        abort_unless(auth()->user()->can('Create Package'), 403);
        [$tours, $flights, $setting, $pricingSetting] = $get->execute();

        return view('smartTT.package.create', compact('tours', 'flights', 'setting', 'pricingSetting'));
    }

    public function store(Request $request, StorePackageAction $action): RedirectResponse
    {
        try {
            abort_unless(auth()->user()->can('Create Package'), 403);
            $action->execute($request->all());
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('packages.index')->with('success', __('Package created successfully'));
    }

    public function show(Package $package): Factory|View|Application
    {
        abort_unless(auth()->user()->can('View Package'), 403);
        $package->load([
            'flight:id,departure_date,arrival_date,airline_id,departure_airport_id,arrival_airport_id',
            'flight.airline:id,name',
            'pricings', 'pricings.guests:id,package_pricing_id'
        ]);

        return view('smartTT.package.show', compact('package'));
    }

    public function edit(Package $package, GetTourAndFlightForCreateAndUpdatePackage $get): Factory|View|Application
    {
        abort_unless(auth()->user()->can('Edit Package'), 403);
        $package->load('flight', 'tour');
        [$tours, $flights] = $get->execute(loadPackageSetting: false, loadPricingSetting: false);

        return view('smartTT.package.edit', compact('package', 'tours', 'flights'));
    }

    public function update(Request $request, Package $package, UpdatePackageAction $action): RedirectResponse
    {
        try {
            abort_unless(auth()->user()->can('Edit Package'), 403);
            $action->execute($request->all(), $package);
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('packages.index')->with('success', __('Package updated successfully'));
    }

    public function destroy(Package $package): RedirectResponse
    {
        abort_unless(auth()->user()->can('Delete Package'), 403);
        $package->delete();

        return redirect()->route('packages.index')->with('success', __('Package deleted successfully'));
    }

    public function audit(Package $package)
    {
        abort_unless(auth()->user()->can('View Package'), 403);
        $logs = Activity::forSubject($package)->get();

        return view('smartTT.package.audit', compact('logs', 'package'));
    }
}
