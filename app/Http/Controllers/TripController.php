<?php

namespace App\Http\Controllers;

use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use function compact;
use function view;

class TripController extends Controller
{

    public function index()
    {
        $trips = Trip::all();
        return view('smartTT.trip.index', compact('trips'));
    }


    public function create()
    {
        return view('smartTT.trip.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'departure_datetime' => 'required',
            'fee' => 'required',
            'tour_id' => 'required',
            'airline_id' => 'required',
            'capacity' => 'required'
        ]);
        Trip::create([
            'departure_datetime' => $request->get('departure_datetime') ?? "N/A",
            'fee' => $request->get('fee') ?? "N/A",
            'tour_id' => $request->get('tour_id') ?? "N/A",
            'airline_id' => $request->get('airline_id') ?? "N/A",
            'capacity' => $request->get('capacity') ?? "N/A",
        ]);
        return Redirect::route('trip.index');
    }


    public function show(Trip $trip)
    {
        return view('smartTT.trip.show', compact('trip'));
    }

    public function edit(Trip $trip)
    {
        return view('smartTT.trip.edit', compact('trip'));
    }

    public function update(Request $request, Trip $trip)
    {
        $trip->update($request->all());
        return Redirect::route('trip.index');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        return Redirect::route('tour.index');
    }
}
