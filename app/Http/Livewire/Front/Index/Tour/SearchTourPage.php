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

    public string $latestDepartTime;
    public Collection $categories;
    public Collection $countrySelection;
    public string $imageUrl;

    public string $default_currency_symbol;

    public bool $stillCanLoad = true;
    public int $limit = 6;

    protected $queryString = [
        'q',
        'dateFrom',
        'dateTo',
        'priceFrom',
        'priceTo',
        'category',
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

        $this->default_currency_symbol = app(GeneralSetting::class)->default_currency_symbol;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.front.index.tour.search-tour-page', [
            'tours' => $this->getTours(),
        ])
            ->extends('front.layouts.app');
    }

    private function getTours()
    {
        if ($this->q !== '') {
            $tours = Tour::search($this->q)
                ->query(function ($query) {
                    return $query->with([
                        'description',
                        'activePackages',
                        'activePackages.activePricings',
                        'media',
                        'countries',
                    ])
                        ->active()
                        ->when($this->dateFrom !== '' && $this->dateTo !== '', function ($query) {
                            return $query->whereHas('activePackages', function ($query) {
                                return $query->where('depart_time', '>=', $this->dateFrom)
                                    ->where('depart_time', '<=', $this->dateTo);
                            });
                        })
                        ->when($this->priceFrom !== 0 && $this->priceTo !== 0, function ($query) {
                            return $query->whereHas('activePackages', function ($query) {
                                return $query->whereHas('activePricings', function ($query) {
                                    return $query->where('price', '>=', $this->priceFrom * 100)
                                        ->where('price', '<=', $this->priceTo * 100);
                                });
                            });
                        })
                        ->when($this->category !== '', function ($query) {
                            return $query->where('category', $this->category);
                        });
                })
                ->get();

            if ($tours->count() <= $this->limit) {
                $this->stillCanLoad = false;
            }

            return $tours->take($this->limit);
        }
        $tours = Tour::query()
            ->with([
                'description',
                'activePackages',
                'activePackages.activePricings',
                'media',
                'countries',
            ])
            ->active()
            ->when($this->dateFrom !== '' && $this->dateTo !== '', function ($query) {
                return $query->whereHas('activePackages', function ($query) {
                    return $query->where('depart_time', '>=', $this->dateFrom)
                        ->where('depart_time', '<=', $this->dateTo);
                });
            })
            ->when($this->priceFrom !== 0 && $this->priceTo !== 0, function ($query) {
                return $query->whereHas('activePackages', function ($query) {
                    return $query->whereHas('activePricings', function ($query) {
                        return $query->where('price', '>=', $this->priceFrom * 100)
                            ->where('price', '<=', $this->priceTo * 100);
                    });
                });
            })
            ->when($this->category !== '', function ($query) {
                return $query->where('category', $this->category);
            });

        if ($tours->count() <= $this->limit) {
            $this->stillCanLoad = false;
        }

        return $tours->limit($this->limit)->get();
    }

    public function getCheapestPrice(Tour $tour): string
    {
        $price = $tour
                ->activePackages
                ->map(function ($package) {
                    return $package->activePricings->sortBy('price')->first();
                })
                ->sortBy('price')
                ->first()
                ->price ?? 0;

        return number_format($price, 2);
    }

    public function loadMore()
    {
        $this->limit += 6;
    }

    public function updatingPriceTo($newValue)
    {
        if (is_numeric($newValue)) {
            $this->priceTo = $newValue;
        }
    }

    public function updatingPriceFrom($newValue)
    {
        if (is_numeric($newValue)) {
            $this->priceFrom = $newValue;
        }
    }
}
