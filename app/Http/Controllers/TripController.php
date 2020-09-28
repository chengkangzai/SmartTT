<?php

namespace App\Http\Controllers;

use App\Airline;
use App\Tour;
use App\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use function compact;
use function view;

class TripController extends Controller
{

    public function index()
    {
        $trips = Trip::paginate(10);
        return view('smartTT.trip.index', compact('trips'));
    }


    public function create()
    {
        $tours = Tour::select('id', 'name')->get();
        $airlines = Airline::select('id', 'name')->get();
//        $flight = Flight::all()
        return view('smartTT.trip.create', compact('tours', 'airlines'));
    }


    public function store(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'fee' => 'required|integer',
            'tour' => 'required',
            'capacity' => 'required',
            'depart_time' => 'required',
            'airline' => 'required',
        ]);

        Trip::create([
            'fee' => $request->get('fee') * 100,
            'tour_id' => $request->get('tour'),
            'capacity' => $request->get('capacity'),
            'depart_time' => Carbon::parse($request->get('depart_time')),
            'airline' => $request->get('airline'),
        ]);
        return Redirect::route('trip.index');
    }


    public function show(Trip $trip)
    {
        return view('smartTT.trip.show', compact('trip'));
    }

    public function edit(Trip $trip)
    {
        $tour = $trip->tour()->first();
        $tours = Tour::select('id', 'name')->get();
        $airlines = Airline::select('id', 'name')->get();
        return view('smartTT.trip.edit', compact('trip', 'tour', 'tours', 'airlines'));
    }

    public function update(Request $request, Trip $trip)
    {
        $trip->update($request->all());
        return Redirect::route('trip.index');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        return Redirect::route('trip.index');
    }
}
