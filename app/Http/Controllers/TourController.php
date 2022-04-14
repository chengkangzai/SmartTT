<?php

namespace App\Http\Controllers;

use App\Actions\Tour\DestroyTourAction;
use App\Actions\Tour\StoreTourAction;
use App\Actions\Tour\UpdateStoreAction;
use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function store(Request $request, StoreTourAction $action): RedirectResponse
    {
        try {
            $action->execute($request->all());
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('tours.index');
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

    public function update(Request $request, Tour $tour, UpdateStoreAction $action): RedirectResponse
    {
        $action->execute($request->all(), $tour);

        return redirect()->route('tours.index');
    }

    public function destroy(Tour $tour, DestroyTourAction $action): RedirectResponse
    {
        try {
            $action->execute([], $tour);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('tours.index');
    }
}
