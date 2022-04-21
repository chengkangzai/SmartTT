<?php

namespace App\Http\Controllers;

use App\Actions\Package\Pricings\AttachPricingToPackageAction;
use App\Actions\Package\Pricings\DestroyPackagePricingAction;
use App\Actions\Package\Pricings\UpdatePackagePricingAction;
use App\Models\Package;
use App\Models\PackagePricing;
use Illuminate\Http\Request;

class PackagePricingController extends Controller
{
    public function edit(PackagePricing $packagePricing)
    {
        return view('smartTT.packagePricing.edit', compact('packagePricing'));
    }

    public function update(Request $request, PackagePricing $packagePricing, UpdatePackagePricingAction $action)
    {
        $action->execute($request->all(), $packagePricing);

        return redirect()->route('packages.show', $packagePricing->package)->with('success', __('Package pricing updated successfully.'));
    }

    public function destroy(PackagePricing $packagePricing, DestroyPackagePricingAction $action)
    {
        $action->execute($packagePricing);

        return redirect()->route('packages.show', $packagePricing->package)->with('success', __('Package pricing deleted successfully.'));
    }

    public function attachToPackage(Request $request, Package $package, AttachPricingToPackageAction $action)
    {
        $action->execute($request->all(), $package);

        return back()->with('success', __('Package pricing attached successfully.'));
    }
}
