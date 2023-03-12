<?php

namespace App\Http\Controllers;

use App\Models\Tour;

class PublicIndexController extends Controller
{
    public function index()
    {
        return view('front.index.index');
    }

    public function tours(Tour $tour)
    {
        abort_if(! $tour->is_active, 404);

        $tour->load([
            'activePackages:id,tour_id,depart_time,is_active',
            'activePackages.activePricings:id,price,name,package_id,available_capacity',
            'activePackages.flight:id,departure_airport_id,arrival_airport_id,airline_id',
            'activePackages.flight.airline:id,country_id,name',
            'description',
            'countries:id,name',
        ]);

        abort_if(! $tour->activePackages->count(), 404);

        $des = $tour
            ->description
            ->map(function ($description) {
                return [
                    'question' => $description->place,
                    'answer' => $description->description,
                ];
            });

        return view('front.index.tours', compact('tour', 'des'));
    }
}
