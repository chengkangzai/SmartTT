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

        return redirect()->route('tours.show', $tourDescription->tour)
            ->with('success', __('Tour description updated successfully'));
    }

    public function edit(TourDescription $tourDescription): Factory|View|Application
    {
        return view('smartTT.tourDescription.edit', compact('tourDescription'));
    }

    public function destroy(TourDescription $tourDescription): RedirectResponse
    {
        $tourDescription->delete();

        return back()->with('success', __('Tour description deleted successfully'));
    }

    public function attachToTour(Request $request, Tour $tour, AttachDescriptionToTourAction $action): RedirectResponse
    {
        $action->execute($request->all(), $tour);

        return back()->with('success', __('Tour description attached successfully'));
    }
}
