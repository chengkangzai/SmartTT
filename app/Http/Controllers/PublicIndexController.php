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
        $tours = Tour::query()
            ->with([
                'media',
                'activePackages:id,is_active,depart_time',
                'activePackages.pricings:id,price,package_id',
                'countries:id,name',
            ])
            ->active()
            ->select(['id', 'name'])
            ->limit(6)
            ->get();

        return view('front.index.index', compact('categories', 'countries', 'imageUrl', 'tours'));
    }
}
