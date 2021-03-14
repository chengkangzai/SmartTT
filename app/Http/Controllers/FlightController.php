<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $flights = Flight::with(['airline', 'depart_airports', 'arrive_airport'])->paginate(10);
        return view('smartTT.flight.index', compact('flights'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): Factory|View|Application
    {
        $airlines = Airline::select(['id', 'name'])->get();
        $airports = Airport::select(['id', 'name', 'ICAO'])->get();
        return view('smartTT.flight.create', compact('airlines', 'airports'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->all();
        $data['depart_time'] = Carbon::parse($request->get('depart_time'));
        $data['arrive_time'] = Carbon::parse($request->get('arrive_time'));
        $data['fee'] = $request->get('fee') * 100;
        Flight::create($data);
        return redirect()->route('flight.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Flight $flight
     * @return Application|Factory|View
     */
    public function show(Flight $flight): Factory|View|Application
    {
        return view('smartTT.flight.show', compact('flight'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Flight $flight
     * @return Application|Factory|View
     */
    public function edit(Flight $flight): Factory|View|Application
    {
        $flight->load(['airline', 'depart_airports', 'arrive_airport']);
        $airlines = Airline::select(['id', 'name'])->get();
        $airports = Airport::select(['id', 'name', 'ICAO'])->get();
        return view('smartTT.flight.edit', compact('flight', 'airlines', 'airports'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Flight $flight
     * @return RedirectResponse
     */
    public function update(Request $request, Flight $flight): RedirectResponse
    {
        $data = $request->all();
        $data['depart_time'] = Carbon::parse($request->get('depart_time'));
        $data['arrive_time'] = Carbon::parse($request->get('arrive_time'));
        $data['fee'] = $request->get('fee') * 100;
        $flight->update($data);
        return redirect()->route('flight.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Flight $flight
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function destroy(Flight $flight): Response|RedirectResponse
    {
        $flight->delete();
        return redirect()->route('flight.index');
    }
}
