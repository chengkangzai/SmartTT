<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Tour;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PublicIndexController extends Controller
{
    public function index()
    {
        $categories = Tour::select('category')->distinct()->pluck('category');
        $imageUrl = Media::whereCollectionName('thumbnail')
            ->whereModelType(Tour::class)
            ->inRandomOrder()
            ->first()
            ->getUrl();
        $countries = Country::select(['id', 'name'])->has('tours')->get();

        return view('front.index.index', compact('categories', 'countries', 'imageUrl'));
    }

    public function tours(Tour $tour)
    {
        $tour->load([
            'activePackages:id,tour_id,depart_time,is_active',
            'activePackages.pricings:id,price,name,package_id,available_capacity',
            'activePackages.flight:id,departure_airport_id,arrival_airport_id,airline_id',
            'activePackages.flight.airline:id,country_id,name',
            'description',
            'countries:id,name',
        ]);

        abort_if(!$tour->activePackages->count(), 404);

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
