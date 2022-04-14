<?php

namespace App\Http\Controllers;

use App\Actions\Tour\TourDescription\AttachDescriptionToTourAction;
use App\Actions\Tour\TourDescription\UpdateTourDescriptionAction;
use App\Models\Tour;
use App\Models\TourDescription;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TourDescriptionController extends Controller
{
    public function update(Request $request, TourDescription $tourDescription, UpdateTourDescriptionAction $action): RedirectResponse
    {
        $action->execute($request->all(), $tourDescription);

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

    public function attachToTour(Request $request, Tour $tour, AttachDescriptionToTourAction $action): RedirectResponse
    {
        $action->execute($request->all(), $tour);

        return redirect()->back();
    }
}
