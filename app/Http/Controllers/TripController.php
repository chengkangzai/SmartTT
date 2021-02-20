<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Trip;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Throwable;
use function compact;
use function view;

/**
 * Class TripController
 * @package App\Http\Controllers
 */
class TripController extends Controller
{

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $trips = Trip::with('tour')->paginate(10);
        return view('smartTT.trip.index', compact('trips'));
    }


    /**
     * @return Application|Factory|View
     */
    public function create(): Factory|View|Application
    {
        $tours = Tour::select(['id', 'name'])->get();
        return view('smartTT.trip.create', compact('tours'));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request): RedirectResponse
    {
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
     * @return Application|Factory|View
     */
    public function show(Trip $trip): Factory|View|Application
    {
        $flights = $trip->flight()->get();
        return view('smartTT.trip.show', compact('trip', 'flights'));
    }

    /**
     * @param Trip $trip
     * @return Application|Factory|View
     */
    public function edit(Trip $trip): Factory|View|Application
    {
        $tour = $trip->tour()->first();
        $tours = Tour::select(['id', 'name'])->get();

        return view('smartTT.trip.edit', compact('trip', 'tour', 'tours'));
    }

    /**
     * @param Request $request
     * @param Trip $trip
     * @return RedirectResponse
     */
    public function update(Request $request, Trip $trip): RedirectResponse
    {
        $trip->update($request->all());
        return Redirect::route('trip.index');
    }

    /**
     * @param Trip $trip
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Trip $trip): RedirectResponse
    {
        $trip->delete();
        return Redirect::route('trip.index');
    }
}
