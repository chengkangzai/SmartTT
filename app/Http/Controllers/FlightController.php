<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use App\Models\Flight;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function compact;
use function redirect;
use function view;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $flights = Flight::with('airline')->paginate(10);
        return view('smartTT.flight.index', compact('flights'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $airlines = Airline::all();
        return view('smartTT.flight.create', compact('airlines'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['depart_time'] = Carbon::parse($request->get('depart_time'));
        $data['arrive_time'] = Carbon::parse($request->get('arrive_time'));
        $data['fee'] = $request->get('fee') * 100;
        Flight::create($data);
        return  redirect()->route('flight.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Flight $flight
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Flight $flight)
    {
        return view('smartTT.flight.show',compact('flight'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Flight $flight
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Flight $flight)
    {
        return view('smartTT.flight.edit',compact('flight'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Flight $flight)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function destroy(Flight $flight)
    {
        //
    }
}
