<?php

namespace App\Http\Livewire\Front\Index\Tour;

use App\Models\Flight;
use App\Models\Package;
use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class PackagesTable extends Component
{
    public Tour $tour;
    public $packages;
    public Collection $months;
    public array $airlines;

    public int $month = 0;
    public int $priceFrom = 0;
    public int $priceTo = 0;
    public int $airlineId = 0;

    public function mount(Tour $tour)
    {
        $this->tour = $tour;
        $this->packages = $tour->activePackages;
        $pricing = $this->packages->map(fn ($package) => $package->pricings->map->price)
            ->flatten()->sort()->values();

        $this->priceFrom = $pricing->first();
        $this->priceTo = $pricing->last();

        $this->months = $tour->activePackages
            ->pluck('depart_time')
            ->mapWithKeys(function ($date) {
                return [
                    (int)$date->format('m') => $date->format('F'),
                ];
            });

        $this->airlines = $tour->activePackages
            ->map(function ($package) {
                return $package->flight->map(fn (Flight $airline) => $airline->airline)->unique()->sort();
            })
            ->flatten()
            ->unique()
            ->toArray();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.front.index.tour.packages-table');
    }

    public function refreshData()
    {
        $this->packages = $this->tour->activePackages
            ->when($this->priceFrom, function (Collection $packages) {
                return $packages->filter(function (Package $package) {
                    return $package->pricings->sortBy('price')->first()->price >= $this->priceFrom;
                });
            })
            ->when($this->priceTo, function (Collection $packages) {
                return $packages->filter(function (Package $package) {
                    return $package->pricings->sortBy('price')->first()->price <= $this->priceTo;
                });
            })
            ->when($this->month != 0, function (Collection $packages) {
                return $packages->filter(function (Package $package) {
                    return $package->depart_time->format('m') == $this->month;
                });
            })
            ->when($this->airlineId != 0, function (Collection $packages) {
                return $packages->filter(function (Package $package) {
                    return $package->flight->filter(function (Flight $flight) {
                        return $flight->airline_id == $this->airlineId;
                    })->isNotEmpty();
                });
            });
    }

    #region Filters
    public function updatedMonth()
    {
        $this->refreshData();
    }

    public function updatedPriceFrom()
    {
        $this->refreshData();
    }

    public function updatedPriceTo()
    {
        $this->refreshData();
    }

    public function updatedAirlineId()
    {
        $this->refreshData();
    }
    #endregion
}
