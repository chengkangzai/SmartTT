<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourDescription;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;


class TourDescriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Tour $tour
     * @return Application|ResponseFactory|Response
     */
    public function store(Request $request, Tour $tour): Response|Application|ResponseFactory
    {
//todo: seems wrong 
        $temp = collect([]);
        $place = $request->get('place');
        $des = $request->get('des');
        ($tour == null)
            ? $tour_id = Tour::find($request->get('tour_id'))->pluck('id')
            : $tour_id = $tour->id;

        for ($i = 0; $i < count($place); $i++) {
            $temp->push([
                'place' => $place[$i],
                'description' => $des[$i],
                'tour_id' => $tour_id
            ]);
        }
        TourDescription::insert($temp->toArray());
        return response('Create Successfully', 200);
    }

    /**
     * @param Request $request
     * @param TourDescription $tourDescription
     * @return RedirectResponse
     */
    public function update(Request $request, TourDescription $tourDescription): RedirectResponse
    {
        $tourDescription->update([
            'place' => $request->get('place'),
            'description' => $request->get('des'),
        ]);
        return redirect()->route('tour.show', ['tour' => $tourDescription->tour()->first()->id]);
    }

    /**
     * @param TourDescription $tourDescription
     * @return Application|Factory|View
     */
    public function edit(TourDescription $tourDescription): Factory|View|Application
    {
        $tourName = Tour::find($tourDescription->tour_id)->first()->name;
        return view('smartTT.tourDescription.edit', compact('tourDescription', 'tourName'));
    }


    /**
     * @param TourDescription $tourDescription
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(TourDescription $tourDescription): RedirectResponse
    {
        $tourDescription->delete();
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param Tour $tour
     * @return RedirectResponse
     */
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
