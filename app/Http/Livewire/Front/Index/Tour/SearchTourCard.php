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

    public function mount(): void
    {
        $this->imageUrl = Media::whereCollectionName('thumbnail')
            ->whereModelType(Tour::class)
            ->inRandomOrder()
            ?->first()
            ?->getUrl() ?? 'https://via.placeholder.com/640x480.png';

        $this->countries = Country::select(['id', 'name'])->has('tours')->get();
        $package = Package::active()->latest('depart_time')->first();
        if ($package) {
            $this->latestDepartTime = $package->depart_time->format('Y-m-d');
        }else{
            $this->latestDepartTime = now()->format('Y-m-d');
        }

        $pricings = PackagePricing::with('activePackage:id')
            ->select(['id', 'price'])
            ->orderBy('price')
            ->get();

        if ($pricings->isEmpty()) {
            $this->priceFrom = 0;
            $this->priceTo = 0;
            return;
        }

        $sortByPrice = $pricings
            ->pluck('price')
            ->map(fn ($price) => (int) $price / 100);

        $this->priceFrom = $sortByPrice->first();
        $this->priceTo = $sortByPrice->last();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.front.index.tour.search-tour-card');
    }
}
