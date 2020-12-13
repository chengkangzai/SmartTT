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

/**
 * Class TripController
 * @package App\Http\Controllers
 */
class TripController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $trips = Trip::with('tour')->paginate(10);
        return view('smartTT.trip.index', compact('trips'));
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $tours = Tour::select('id', 'name')->get();
        return view('smartTT.trip.create', compact('tours'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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


    /**
     * @param Trip $trip
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Trip $trip)
    {
        $flights = $trip->flight()->get();
        return view('smartTT.trip.show', compact('trip', 'flights'));
    }

    /**
     * @param Trip $trip
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Trip $trip)
    {
        $tour = $trip->tour()->first();
        $tours = Tour::select('id', 'name')->get();

        return view('smartTT.trip.edit', compact('trip', 'tour', 'tours'));
    }

    /**
     * @param Request $request
     * @param Trip $trip
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Trip $trip)
    {
        $trip->update($request->all());
        return Redirect::route('trip.index');
    }

    /**
     * @param Trip $trip
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Trip $trip)
    {
        $trip->delete();
        return Redirect::route('trip.index');
    }
}
