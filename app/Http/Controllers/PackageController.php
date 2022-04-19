<?php

namespace App\Http\Controllers;

use App\Actions\Package\GetTourAndFlightForCreateAndUpdatePackage;
use App\Actions\Package\StorePackageAction;
use App\Actions\Package\UpdatePackageAction;
use App\Models\Package;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PackageController extends Controller
{
    public function index(): View|Factory|Application
    {
        $packages = Package::with('tour', 'flight.airline:id,name')->orderByDesc('id')->paginate();

        return view('smartTT.package.index', compact('packages'));
    }

    public function create(GetTourAndFlightForCreateAndUpdatePackage $get): Factory|View|Application
    {
        [$tours, $flights] = $get->execute();

        return view('smartTT.package.create', compact('tours', 'flights'));
    }

    public function store(Request $request, StorePackageAction $action): RedirectResponse
    {
        try {
            $action->execute($request->all());

        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
        return redirect()->route('packages.index');
    }

    public function show(Package $package): Factory|View|Application
    {
        $package->load('flight', 'flight.airline');

        return view('smartTT.package.show', compact('package'));
    }

    public function edit(Package $package, GetTourAndFlightForCreateAndUpdatePackage $get): Factory|View|Application
    {
        $package->load('flight', 'tour');
        [$tours, $flights] = $get->execute();

        return view('smartTT.package.edit', compact('package', 'tours', 'flights'));
    }

    public function update(Request $request, Package $package, UpdatePackageAction $action): RedirectResponse
    {
        try {
            $action->execute($request->all(), $package);

        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('packages.index');
    }

    public function destroy(Package $package): RedirectResponse
    {
        $package->delete();

        return redirect()->route('packages.index');
    }
}
