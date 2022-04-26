<?php

namespace App\Http\Controllers;

use App\Actions\Flight\StoreFlightAction;
use App\Actions\Flight\UpdateFlightAction;
use App\Models\Airline;
use App\Models\Flight;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FlightController extends Controller
{
    public function index(): Factory|View|Application
    {
        $flights = Flight::with(['airline:id,name', 'depart_airport:id,IATA', 'arrive_airport:id,IATA'])->orderByDesc('id')->paginate(10);

        return view('smartTT.flight.index', compact('flights'));
    }

    public function create(): Factory|View|Application
    {
        $airlines = Airline::select(['id', 'name'])->get();

        return view('smartTT.flight.create', compact('airlines'));
    }

    public function store(Request $request, StoreFlightAction $action): RedirectResponse
    {
        $action->execute($request->all());

        return redirect()->route('flights.index')->with('success', __('Flight created successfully.'));
    }

    public function show(Flight $flight): Factory|View|Application
    {
        return view('smartTT.flight.show', compact('flight'));
    }

    public function edit(Flight $flight): Factory|View|Application
    {
        $flight->load(['airline', 'depart_airport', 'arrive_airport']);
        $airlines = Airline::select(['id', 'name'])->get();

        return view('smartTT.flight.edit', compact('flight', 'airlines'));
    }

    public function update(Request $request, Flight $flight, UpdateFlightAction $action): RedirectResponse
    {
        $action->execute($request->all(), $flight);

        return redirect()->route('flights.index')->with('success', __('Flight updated successfully.'));
    }

    public function destroy(Flight $flight): Response|RedirectResponse
    {
        $flight->delete();

        return redirect()->route('flights.index')->with('success', __('Flight deleted successfully.'));
    }
}
