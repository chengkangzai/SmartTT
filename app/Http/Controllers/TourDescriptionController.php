<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use function collect;
use function compact;
use function count;
use function view;

class TourDescriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Tour $tour
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request, Tour $tour = null)
    {
//        add validate if required
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, TourDescription $tourDescription)
    {
        $tourDescription->update([
            'place' => $request->get('place'),
            'description' => $request->get('des'),
        ]);
        return Redirect::route('tour.show', ['tour' => $tourDescription->tour()->first()->id]);
    }

    /**
     * @param TourDescription $tourDescription
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(TourDescription $tourDescription)
    {
        $tourName = Tour::find($tourDescription->tour_id)->first()->name;
        return view('smartTT.tourDescription.edit', compact('tourDescription', 'tourName'));
    }


    /**
     * @param TourDescription $tourDescription
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(TourDescription $tourDescription)
    {
        $tourDescription->delete();
        return Redirect::back();
    }

    /**
     * @param Request $request
     * @param Tour $tour
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attachToTour(Request $request, Tour $tour)
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
        return Redirect::back();
    }
}
