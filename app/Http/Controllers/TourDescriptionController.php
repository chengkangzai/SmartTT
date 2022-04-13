<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourDescription;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TourDescriptionController extends Controller
{
    public function update(Request $request, TourDescription $tourDescription): RedirectResponse
    {
        $tourDescription->update([
            'place' => $request->get('place'),
            'description' => $request->get('des'),
        ]);

        return redirect()->route('tours.show', ['tour' => $tourDescription->tour()->first()->id]);
    }

    public function edit(TourDescription $tourDescription): Factory|View|Application
    {
        $tourName = Tour::find($tourDescription->tour_id)->first()->name;

        return view('smartTT.tourDescription.edit', compact('tourDescription', 'tourName'));
    }

    public function destroy(TourDescription $tourDescription): RedirectResponse
    {
        $tourDescription->delete();

        return redirect()->back();
    }

    public function attachToTour(Request $request, Tour $tour): RedirectResponse
    {
        $request->validate([
            'place' => 'required',
            'des' => 'required',
        ]);
        TourDescription::create([
            'place' => $request->get('place'),
            'description' => $request->get('des'),
            'tour_id' => $tour->id,
        ]);

        return redirect()->back();
    }
}
