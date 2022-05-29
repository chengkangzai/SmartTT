<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Tour;

class PublicIndexController extends Controller
{
    public function index()
    {
        $categories = Tour::select('category')->distinct()->pluck('category');
        $imageUrl = Tour::inRandomOrder()->first()->getFirstMedia('thumbnail')?->getUrl();
        $countries = Country::select(['id', 'name'])->has('tours')->get();

        return view('front.index.index', compact('categories', 'countries', 'imageUrl'));
    }
}
