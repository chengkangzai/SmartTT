<?php

namespace App\Http\Livewire\Front\Index\Tour;

use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\Settings\GeneralSetting;
use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class SearchTourPage extends Component
{
    public string $q = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public int $priceFrom = 0;
    public int $priceTo = 0;
    public string $category = '';
    public int $capacity = 0;

    public string $latestDepartTime;
    public Collection $categories;
    public Collection $countrySelection;
    public string $imageUrl;

    public Collection $tours;
    public string $default_currency_symbol;

    public mixed $limit = 6;
    public bool $stillCanLoad = true;

    protected $queryString = [
        'q',
        'dateFrom',
        'dateTo',
        'priceFrom',
        'priceTo',
        'category',
        'capacity',
    ];

    public function mount()
    {
        $sortByPrice = PackagePricing::with('activePackage:id')
            ->select(['id', 'price'])
            ->orderBy('price')
            ->get()
            ->pluck('price');

        $this->priceFrom = $sortByPrice->first();
        $this->priceTo = $sortByPrice->last();

        $this->categories = Tour::select('category')->distinct()->pluck('category');
        $this->latestDepartTime = Package::active()->select('depart_time')->latest('depart_time')->first()->depart_time->format('Y-m-d');

        $this->getTours();

        $this->default_currency_symbol = app(GeneralSetting::class)->default_currency_symbol;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.front.index.tour.search-tour-page')
            ->extends('front.layouts.app');
    }

    private function getTours()
    {
        $this->tours = Tour::query()
            ->with([
                'description',
                'activePackages',
                'activePackages.pricings',
                'media',
            ])
            ->when($this->q, function ($query) {
                return $query
                    ->orWhere('name', 'like', "%$this->q%")
                    ->orWhere('tour_code', 'like', "%$this->q%")
                    ->whereHas('description', function ($query) {
                        return $query
                            ->orWhere('description', 'like', "%$this->q%")
                            ->orWhere('place', 'like', "%$this->q%");
                    });
            })
            ->when($this->category !== '', function ($query) {
                return $query->orWhere('category', $this->category);
            })
            ->when($this->dateFrom, function ($query) {
                return $query->whereHas('packages', function ($query) {
                    return $query->where('depart_time', '>=', $this->dateFrom);
                });
            })
            ->when($this->dateTo, function ($query) {
                return $query->whereHas('packages', function ($query) {
                    return $query->where('depart_time', '<=', $this->dateTo);
                });
            })
            ->when($this->priceFrom, function ($query) {
                return $query->whereHas('activePackages', function ($query) {
                    return $query->whereHas('pricings', function ($query) {
                        return $query->orWhere('price', '>=', $this->priceFrom);
                    });
                });
            })
            ->when($this->priceTo, function ($query) {
                return $query->whereHas('activePackages', function ($query) {
                    return $query->whereHas('pricings', function ($query) {
                        return $query->orWhere('price', '<=', $this->priceTo);
                    });
                });
            })
            ->when($this->capacity, function ($query) {
                return $query->whereHas('activePackages', function ($query) {
                    return $query->whereHas('pricings', function ($query) {
                        return $query->where('available_capacity', '>=', $this->capacity);
                    });
                });
            })
            ->limit($this->limit)
            ->get();

        if ($this->tours->count() < $this->limit) {
            $this->stillCanLoad = false;
        }
    }

    public function getCheapestPrice(Tour $tour): string
    {
        $price = $tour
                ->activePackages
                ->map(function ($package) {
                    return $package->pricings->sortBy('price')->first();
                })
                ->sortBy('price')
                ->first()
                ->price ?? 0;

        return number_format($price, 2);
    }

    public function loadMore()
    {
        $this->limit += 6;
        $this->getTours();
    }

    #region Attribute Updated
    public function updatedDateForm()
    {
        $this->getTours();
    }

    public function updatedCategory()
    {
        $this->getTours();
    }

    public function updatedCapacity()
    {
        $this->getTours();
    }

    public function updatedDateTo()
    {
        $this->getTours();
    }

    public function updatedPriceForm()
    {
        $this->getTours();
    }

    public function updatedPriceTo()
    {
        $this->getTours();
    }

    public function updatedQ()
    {
        $this->getTours();
    }
    #endregion
}
