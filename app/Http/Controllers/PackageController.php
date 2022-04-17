<?php

namespace App\Http\Controllers;

use App\Actions\Package\StorePackageAction;
use App\Actions\Package\UpdatePackageAction;
use App\Models\Flight;
use App\Models\Package;
use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(): View|Factory|Application
    {
        $trips = Package::with('tour')->paginate(10);

        return view('smartTT.package.index', compact('trips'));
    }

    public function create(): Factory|View|Application
    {
        $tours = Tour::select(['id', 'name'])->get();
        $flights = Flight::with('airline:id,name')
            ->select(['id', 'airline_id', 'depart_time', 'arrive_time'])
            ->where('depart_time', ">=", now())
            ->where('arrive_time', ">=", now())
            ->orderBy('depart_time')
            ->orderBy('arrive_time')
            ->get()
            ->map(function ($flight) {
                $flight->text = $flight->airline->name . " (" . $flight->depart_time->format('d/m/Y H:i') . ") -> (" . $flight->arrive_time->format('d/m/Y H:i') . ")";

                return $flight;
            });

        return view('smartTT.package.create', compact('tours', 'flights'));
    }

    public function store(Request $request, StorePackageAction $action): RedirectResponse
    {
        try {
            $action->execute($request->all());

            return redirect()->route('packages.index');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function show(Package $trip): Factory|View|Application
    {
        $flights = $trip->flight()->get();

        return view('smartTT.package.show', compact('trip', 'flights'));
    }

    public function edit(Package $trip): Factory|View|Application
    {
        $tour = $trip->tour()->first();
        $tours = Tour::select(['id', 'name'])->get();
        $flights = $trip->flight()->get();

        return view('smartTT.package.edit', compact('trip', 'tour', 'tours', 'flights'));
    }

    public function update(Request $request, Package $trip, UpdatePackageAction $action): RedirectResponse
    {
        try {
            $action->execute($request->all(), $trip);

            return redirect()->route('packages.index');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function destroy(Package $trip): RedirectResponse
    {
        $trip->delete();

        return redirect()->route('packages.index');
    }
}
