<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Flight;
use App\Models\Settings\GeneralSetting;
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
            'activePackages',
            'activePackages.pricings',
            'activePackages.flight.airline',
            'description',
        ]);
        $setting = app(GeneralSetting::class);

        $cheapestPackagePricing = $tour
            ->activePackages
            ->map(function ($package) {
                return $package->pricings->sortBy('price')->first();
            })
            ->sortBy('price')
            ->first();

        $des = $tour
            ->description
            ->map(function ($description) {
                return [
                    'question' => $description->place,
                    'answer' => $description->description,
                ];
            });

        $airlines = $tour
            ->activePackages
            ->map(function ($package) {
                return $package->flight->map(fn (Flight $airline) => $airline->airline)->unique()->sort();
            })
            ->flatten()
            ->unique()
            ->sort();

        return view('front.index.tours', compact('tour', 'setting', 'cheapestPackagePricing', 'des', 'airlines'));
    }
}
