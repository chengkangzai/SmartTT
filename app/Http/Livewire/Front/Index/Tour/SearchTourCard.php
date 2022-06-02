<?php

namespace App\Http\Livewire\Front\Index\Tour;

use App\Models\Country;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SearchTourCard extends Component
{
    public string $latestDepartTime;
    public Collection $categories;
    public Collection $countries;
    public string $imageUrl;

    public int $priceFrom;
    public int $priceTo;

    public function mount()
    {
        $this->imageUrl = Media::whereCollectionName('thumbnail')
            ->whereModelType(Tour::class)
            ->inRandomOrder()
            ->first()
            ->getUrl();

        $this->categories = Tour::select('category')->distinct()->pluck('category');
        $this->countries = Country::select(['id', 'name'])->has('tours')->get();
        $this->latestDepartTime = Package::active()->latest('depart_time')->first()->depart_time->format('Y-m-d');

        $sortByPrice = PackagePricing::with('activePackage:id')
            ->select(['id', 'price'])
            ->orderBy('price')
            ->get()
            ->pluck('price');

        $this->priceFrom = $sortByPrice->first();
        $this->priceTo = $sortByPrice->last();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.front.index.tour.search-tour-card');
    }
}
