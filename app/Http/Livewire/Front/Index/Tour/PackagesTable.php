<?php

namespace App\Http\Livewire\Front\Index\Tour;

use App\Filament\Resources\BookingResource;
use App\Models\Package;
use App\Models\Tour;
use Carbon\Carbon;
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

    public function mount(Tour $tour)
    {
        $this->tour = $tour;
        $this->getPackages();
        $pricing = $this->packages->map(fn (Package $package) => $package->activePricings->map->price)
            ->flatten()->sort()->values();

        $this->priceFrom = $pricing->first();
        $this->priceTo = $pricing->last();

        $this->months = $tour->activePackages
            ->pluck('depart_time')
            ->mapWithKeys(function (Carbon $date) {
                return [
                    (int)$date->format('m') => $date->translatedFormat('F'),
                ];
            });
    }

    public function render(): Factory|View|Application
    {
        $this->getPackages();

        return view('livewire.front.index.tour.packages-table');
    }

    public function getPackages()
    {
        $this->packages = $this->tour->activePackages
            ->when($this->priceFrom, function (Collection $packages) {
                return $packages->filter(function (Package $package) {
                    return $package->pricings->sortBy('price')->first()->price >= $this->priceFrom;
                });
            })
            ->when($this->priceTo, function (Collection $packages) {
                return $packages->filter(function (Package $package) {
                    return $package->pricings->sortByDesc('price')->first()->price <= $this->priceTo;
                });
            })
            ->when($this->month != 0, function (Collection $packages) {
                return $packages->filter(function (Package $package) {
                    return $package->depart_time->format('m') == $this->month;
                });
            });
    }

    public function generateBookNowLink(int $packageId): string
    {
        return BookingResource::getUrl('create', ['package_id' => $packageId]);
    }
}
