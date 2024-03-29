<?php

namespace App\Http\Livewire\Front\Index\Tour;

use App\Filament\Resources\BookingResource;
use App\Models\PackagePricing;
use App\Models\Settings\GeneralSetting;
use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PriceCard extends Component
{
    public string $default_currency_symbol;

    public Tour $tour;

    public int $packageId;

    public PackagePricing $cheapestPackagePricing;

    public function mount(Tour $tour): void
    {
        $this->tour = $tour;

        $this->default_currency_symbol = app(GeneralSetting::class)->default_currency_symbol;

        if (app(GeneralSetting::class)->site_mode !== 'Enquiry') {
            $this->packageId = $tour->activePackages->first()->id;
            $this->cheapestPackagePricing = $tour
                ->activePackages
                ->map(function ($package) {
                    return $package->activePricings->sortBy('price')->first();
                })
                ->sortBy('price')
                ->first();
        }
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.front.index.tour.price-card');
    }

    public function generateBookNowLink(int $packageId): string
    {
        return BookingResource::getUrl('create', ['package_id' => $packageId]);
    }
}
