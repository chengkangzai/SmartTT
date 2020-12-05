<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Trip;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use function compact;
use function view;

class TripController extends Controller
{

    public function index()
    {
        //TODO preload with airline
        $trips = Trip::with('flight')->with('tour')->take(10)->paginate(10);
        return view('smartTT.trip.index', compact('trips'));
    }


    public function create()
    {
        $tours = Tour::select('id', 'name')->get();
        return view('smartTT.trip.create', compact('tours'));
    }


    public function store(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'fee' => 'required|integer',
            'tour' => 'required',
            'capacity' => 'required',
            'depart_time' => 'required',
            'flight' => 'required|array'
        ]);

        DB::transaction(function () use ($request) {
            $trip = Trip::create([
                'fee' => $request->get('fee') * 100,
                'tour_id' => $request->get('tour'),
                'capacity' => $request->get('capacity'),
                'depart_time' => Carbon::parse($request->get('depart_time')),
            ]);

            $trip->flight()->attach($request->get('flight'));
        });


        return Redirect::route('trip.index');
    }


    public function show(Trip $trip)
    {
        $flights = $trip->flight()->get();
        return view('smartTT.trip.show', compact('trip', 'flights'));
    }

    public function edit(Trip $trip)
    {
        $tour = $trip->tour()->first();
        $tours = Tour::select('id', 'name')->get();

        return view('smartTT.trip.edit', compact('trip', 'tour', 'tours'));
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
