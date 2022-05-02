<?php

namespace App\Http\Livewire\Booking;

use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\Settings\GeneralSetting;
use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use function app;
use function collect;

class CreateBookingCard extends Component
{
    public string $defaultCurrency;
    public int $currentStep = 1;

    public Collection $tours;
    public int $tour = 0;

    public Collection $packages;
    public int $package = 0;

    public Collection $pricings;
    public int $pricing = 0;
    public array $pricingsHolder;

    public int $totalPrice = 0;

    public array $guests = [];

    public function mount()
    {
        $this->tours = Tour::active()->get();
        $this->defaultCurrency = app(GeneralSetting::class)->default_currency;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.booking.create-booking-card', [
            'tours' => $this->tours,
        ]);
    }

    public function updatedTour()
    {
        if ($this->tour == 0) {
            $this->addError('tour', __('Please select a tour'));
            return;
        }
        if ($this->getErrorBag()->has('tour')) {
            $this->resetErrorBag();
        }
        $this->currentStep++;
        $this->packages = Package::where('tour_id', $this->tour)->active()->get();
    }

    public function updatedPackage()
    {
        if ($this->package == 0) {
            $this->addError('package', __('Please select a package'));
            return;
        }
        if ($this->getErrorBag()->has('package')) {
            $this->resetErrorBag();
        }
        $this->currentStep++;
        $this->pricings = PackagePricing::query()
            ->where('package_id', $this->package)
            ->where('available_capacity', '>', 1)
            ->active()
            ->orderBy('price')
            ->get();
        $this->pricingsHolder = $this->pricings->toArray();

        $this->addNewGuest();
    }

    public function addNewGuest()
    {
        $cheapestPricing = $this->pricings->first();
        $this->guests[] = ['name' => '', 'pricing' => $cheapestPricing->id, 'price' => $cheapestPricing->price];
    }

    public function removeGuest($index)
    {
        unset($this->guests[$index]);
        $this->guests = array_values($this->guests);
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function updatePrice($index)
    {
        $this->guests[$index]['price'] = $this->pricings->find($this->guests[$index]['pricing'])->price;
        $this->totalPrice = collect($this->guests)->sum('price');
    }

    public function nextStep()
    {
        if ($this->currentStep == 1) {
            $this->updatedTour();
        } elseif ($this->currentStep == 2) {
            $this->updatedPackage();
        } elseif ($this->currentStep == 3) {
            $this->currentStep++;
        }

//        $this->currentStep++;
    }


}
