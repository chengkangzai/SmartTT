<?php

namespace App\Http\Controllers;

use App\Actions\Package\Pricings\AttachPricingToPackageAction;
use App\Actions\Package\Pricings\DestroyPackagePricingAction;
use App\Actions\Package\Pricings\UpdatePackagePricingAction;
use App\Models\Package;
use App\Models\PackagePricing;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class PackagePricingController extends Controller
{
    public function edit(PackagePricing $packagePricing)
    {
        abort_unless(auth()->user()->can('Edit Package Pricing'), 403);

        return view('smartTT.packagePricing.edit', compact('packagePricing'));
    }

    public function update(Request $request, PackagePricing $packagePricing, UpdatePackagePricingAction $action)
    {
        abort_unless(auth()->user()->can('Edit Package Pricing'), 403);
        $action->execute($request->all(), $packagePricing);

        return redirect()->route('packages.show', $packagePricing->package)->with('success', __('Package pricing updated successfully.'));
    }

    public function destroy(PackagePricing $packagePricing, DestroyPackagePricingAction $action)
    {
        abort_unless(auth()->user()->can('Delete Package Pricing'), 403);
        $action->execute($packagePricing);

        return redirect()->route('packages.show', $packagePricing->package)->with('success', __('Package pricing deleted successfully.'));
    }

    public function attachToPackage(Request $request, Package $package, AttachPricingToPackageAction $action)
    {
        abort_unless(auth()->user()->can('Edit Package'), 403);
        $action->execute($request->all(), $package);

        return back()->with('success', __('Package pricing attached successfully.'));
    }

    public function audit(PackagePricing $packagePricing)
    {
        abort_unless(auth()->user()->can('Audit Package Pricing'), 403);
        $logs = Activity::forSubject($packagePricing)->get();

        return view('smartTT.packagePricing.audit', compact('logs', 'packagePricing'));
    }
}
