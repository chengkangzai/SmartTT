<?php

namespace App\Http\Livewire\Front\Index\Tour;

use App\Models\Flight;
use App\Models\Package;
use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;
use Log;

class PackagesTable extends Component
{
    public Tour $tour;
    public $packages;
    public Collection $months;
    public array $airlines;

    public int $month = 7;
    public int $priceFrom = 0;
    public int $priceTo = 0;
    public int $airlineId;

    public function mount(Tour $tour)
    {
        $this->tour = $tour;
        $this->packages = $tour->activePackages;
        $pricing = $this->packages->map(function ($package) {
            return $package->pricings->sortBy('price')->first();
        })->sortBy('price');

        $this->priceFrom = $pricing->first()->price;
        $this->priceTo = $pricing->last()->price;

        $this->months = $tour->activePackages
            ->pluck('depart_time')
            ->mapWithKeys(function ($date) {
                return [
                    (int)$date->format('m') => $date->format('F'),
                ];
            });
        $this->month = $this->months->keys()->first();

        $this->airlines = $tour->activePackages
            ->map(function ($package) {
                return $package->flight->map(fn(Flight $airline) => $airline->airline)->unique()->sort();
            })
            ->flatten()
            ->unique()
            ->toArray();
        $this->airlineId = $this->airlines[0]?->id ?? 0;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.front.index.tour.packages-table');
    }

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

    public function refreshData()
    {
        $this->packages = $this->tour->activePackages
            ->when($this->priceFrom, fn(Collection $packages) => $packages->filter(fn(Package $package) => $package->pricings->sortBy('price')->first()->price >= $this->priceFrom))
            ->when($this->priceTo, fn(Collection $packages) => $packages->filter(fn(Package $package) => $package->pricings->sortBy('price')->first()->price <= $this->priceTo))
            ->when($this->month, fn(Collection $packages) => $packages->filter(fn(Package $package) => $package->depart_time->format('m') == $this->month))
            ->when($this->airlineId, fn(Collection $packages) => $packages->filter(fn(Package $package) => $package->flight->filter(fn(Flight $flight) => $flight->airline_id == $this->airlineId)->count() > 0));

    }
}
