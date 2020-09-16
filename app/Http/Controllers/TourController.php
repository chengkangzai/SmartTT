<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeTourRequest;
use App\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use function compact;
use function redirect;
use function view;

class TourController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $tours = Tour::all();
        return view('smartTT.tour.index', compact('tours'));
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('smartTT.tour.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param storeTourRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(storeTourRequest $request)
    {
        DB::transaction(function () use ($request) {
            $tour = Tour::create([
                'tour_code' => $request->get('tour_code'),
                'name' => $request->get('name'),
                'destination' => $request->get('destination'),
                'category' => $request->get('category'),
                'itinerary_url' => Storage::putFile('public/Tour/itinerary', $request->file('itinerary'), 'public'),
                'thumbnail_url' => Storage::putFile('public/Tour/thumbnail', $request->file('thumbnail'), 'public'),
            ]);
            $TD = new TourDescriptionController();
            $TD->store($request, $tour);
        });
        return Redirect::route('tour.index');
    }


    /**
     * @param Tour $tour
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Tour $tour)
    {
        $itineraryUrl = Storage::url($tour->itinerary_url);
        $thumbnailUrl = Storage::url($tour->thumbnail_url);
        $tourDes = $tour->description()->get();
        return view('smartTT.tour.show', compact('tour', 'itineraryUrl', 'thumbnailUrl', 'tourDes'));
    }

    /**
     * @param Tour $tour
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Tour $tour)
    {
        return view('smartTT.tour.edit', compact('tour'));
    }

    /**
     * @param Request $request
     * @param Tour $tour
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Tour $tour)
    {
        $request->validate([
            'name' => 'required',
//            'tour_code' => 'required|unique:App\Tour,tour_code,except'.$tour->tour_code,
            'destination' => 'required',
            'category' => 'required',
            'itinerary' => 'mimes:pdf',
            'thumbnail' => 'mimes:jpeg,bmp,png',
        ]);
        if ($request->get('tour_code') !== $tour->tour_code) {
            $count = Tour::where('tour_code', $request->get('tour_code'))->count();
            if ($count !== 0) return redirect()->back()->withErrors(['tour_code' => 'Error Message'])->withInput();
        }

        if ($request->hasFile('itinerary')) Storage::delete($tour->itinerary_url);
        if ($request->hasFile('thumbnail')) Storage::delete($tour->thumbnail_url);

        $tour->update([
            'tour_code' => $request->get('tour_code'),
            'name' => $request->get('name'),
            'destination' => $request->get('destination'),
            'category' => $request->get('category'),
            'itinerary_url' => Storage::putFile('public/Tour/itinerary', $request->file('itinerary'), 'public'),
            'thumbnail_url' => Storage::putFile('public/Tour/thumbnail', $request->file('thumbnail'), 'public'),
        ]);
        return Redirect::route('tour.index');
    }


    /**
     * @param Tour $tour
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Tour $tour)
    {
        $tour->description()->delete();
        Storage::delete([$tour->itinerary_url, $tour->thumbnail_url]);
        $tour->delete();
        return Redirect::route('tour.index');
    }
}
