<?php

namespace App\Http\Controllers;

use App\Actions\Trip\StoreTripAction;
use App\Actions\Trip\UpdateTripAction;
use App\Models\Flight;
use App\Models\Tour;
use App\Models\Trip;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index(): View|Factory|Application
    {
        $trips = Trip::with('tour')->paginate(10);

        return view('smartTT.trip.index', compact('trips'));
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

        return view('smartTT.trip.create', compact('tours', 'flights'));
    }

    public function store(Request $request, StoreTripAction $action): RedirectResponse
    {
        try {
            $action->execute($request->all());
            return redirect()->route('trips.index');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function show(Trip $trip): Factory|View|Application
    {
        $flights = $trip->flight()->get();

        return view('smartTT.trip.show', compact('trip', 'flights'));
    }

    public function edit(Trip $trip): Factory|View|Application
    {
        $tour = $trip->tour()->first();
        $tours = Tour::select(['id', 'name'])->get();
        $flights = $trip->flight()->get();

        return view('smartTT.trip.edit', compact('trip', 'tour', 'tours', 'flights'));
    }

    public function update(Request $request, Trip $trip, UpdateTripAction $action): RedirectResponse
    {
        try {
            $action->execute($request->all(), $trip);
            return redirect()->route('trips.index');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function destroy(Trip $trip): RedirectResponse
    {
        $trip->delete();

        return redirect()->route('trips.index');
    }
}
