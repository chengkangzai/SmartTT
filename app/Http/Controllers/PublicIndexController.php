<?php

namespace App\Http\Controllers;

use App\Models\Settings\GeneralSetting;
use App\Models\Tour;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PublicIndexController extends Controller
{
    public function index()
    {
        $imageUrl = Media::whereCollectionName('thumbnail')
            ->whereModelType(Tour::class)
            ->inRandomOrder()
            ?->first()
            ?->getUrl() ?? 'https://via.placeholder.com/640x480.png';

        return view('front.index.index', [
            'imageUrl' => $imageUrl,
        ]);
    }

    public function tours(Tour $tour)
    {
        abort_if(! $tour->is_active, 404);

        abort_if(app(GeneralSetting::class)->site_mode !== 'Enquiry' && $tour->activePackages->isEmpty(), 404);

        $tour->load([
            'media',
            'activePackages:id,tour_id,depart_time,is_active',
            'activePackages.activePricings:id,price,name,package_id,available_capacity',
            'activePackages.flight:id,departure_airport_id,arrival_airport_id,airline_id',
            'activePackages.flight.airline:id,country_id,name',
            'description',
            'countries:id,name',
        ]);

        $des = $tour
            ->description
            ->map(fn ($description) => [
                'question' => $description->place,
                'answer' => $description->description,
            ]);

        return view('front.index.tours', compact('tour', 'des'));
    }
}
