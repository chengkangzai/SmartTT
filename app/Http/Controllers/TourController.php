<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTourRequest;
use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TourController extends Controller
{
    public function index(): Factory|View|Application
    {
        $tours = Tour::paginate(10);

        return view('smartTT.tour.index', compact('tours'));
    }

    public function create(): Factory|View|Application
    {
        return view('smartTT.tour.create');
    }

    public function store(StoreTourRequest $request): RedirectResponse
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

            $place = $request->get('place');
            $des = $request->get('des');

            for ($i = 0; $i < count($place); $i++) {
                $tour->description()->create([
                    'place' => $place[$i],
                    'description' => $des[$i],
                    'tour_id' => $tour->id,
                ]);
            }
        });

        return Redirect::route('tours.index');
    }

    public function show(Tour $tour): Factory|View|Application
    {
        $itineraryUrl = Storage::url($tour->itinerary_url);
        $thumbnailUrl = Storage::url($tour->thumbnail_url);
        $tourDes = $tour->description()->paginate(9);

        return view('smartTT.tour.show', compact('tour', 'itineraryUrl', 'thumbnailUrl', 'tourDes'));
    }

    public function edit(Tour $tour): Factory|View|Application
    {
        return view('smartTT.tour.edit', compact('tour'));
    }

    public function update(Request $request, Tour $tour): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'tour_code' => 'required|unique:tours,tour_code,' . $tour->id,
            'destination' => 'required',
            'category' => 'required',
            'itinerary' => 'mimes:pdf',
            'thumbnail' => 'mimes:jpeg,bmp,png',
        ]);
        if ($request->get('tour_code') !== $tour->tour_code) {
            $count = Tour::where('tour_code', $request->get('tour_code'))->count();
            if ($count !== 0) {
                return redirect()->back()->withErrors(['tour_code' => 'Error Message'])->withInput();
            }
        }

        if ($request->hasFile('itinerary')) {
            Storage::delete($tour->itinerary_url);
        }
        if ($request->hasFile('thumbnail')) {
            Storage::delete($tour->thumbnail_url);
        }

        $tour->update([
            'tour_code' => $request->get('tour_code'),
            'name' => $request->get('name'),
            'destination' => $request->get('destination'),
            'category' => $request->get('category'),
            'itinerary_url' => Storage::putFile('public/Tour/itinerary', $request->file('itinerary'), 'public'),
            'thumbnail_url' => Storage::putFile('public/Tour/thumbnail', $request->file('thumbnail'), 'public'),
        ]);

        return Redirect::route('tours.index');
    }

    public function destroy(Tour $tour): RedirectResponse
    {
        if ($tour->trips->isNotEmpty()) {
            $trips = $tour->trips->all();
            $holder = '';
            foreach ($trips as $trip) {
                $holder .= $trip->id . ',';
            }

            return redirect()->back()->withErrors('The tour cant be deleted because trip id ' . $holder . ' associate with it');
        }

        $tour->description()->delete();
        $tour->delete();
        Storage::delete([$tour->itinerary_url, $tour->thumbnail_url]);

        return Redirect::route('tours.index');
    }
}
