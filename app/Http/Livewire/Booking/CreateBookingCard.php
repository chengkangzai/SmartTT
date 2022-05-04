<?php

namespace App\Http\Livewire\Booking;

use function app;
use App\Models\Booking;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\Settings\BookingSetting;
use App\Models\Settings\GeneralSetting;
use App\Models\Tour;
use function array_filter;
use function array_keys;
use function collect;
use function count;
use DB;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class CreateBookingCard extends Component
{
    #region properties
    public Collection $tours;
    public int $tour = 0;
    public Tour $tourModel;

    public Collection $packages;
    public int $package = 0;
    public Package $packageModel;

    public Collection $pricings;
    public int $pricing = 0;
    public array $pricingsHolder;

    public int $totalPrice = 0;

    public array $guests = [];

    private Booking $booking;
    #endregion

    public int $currentStep = 1;
    public string $defaultCurrency;
    public int $charge_per_child;

    public function mount()
    {
        $this->tours = Tour::active()->get();
        $this->defaultCurrency = app(GeneralSetting::class)->default_currency;
        $this->charge_per_child = app(BookingSetting::class)->charge_per_child;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.booking.create-booking-card', [
            'tours' => $this->tours,
        ]);
    }

    #region Step 1 - Select Tour
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

        $this->tourModel = Tour::find($this->tour);
    }
    #endregion

    #region Step 2 - Select Package
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
        $this->updatePricings();
        if (count($this->guests) == 0) {
            $this->addNewGuest();
        } else {
            $this->updatePrice(0);
        }

        $this->packageModel = Package::find($this->package);
    }

    private function updatePricings(): void
    {
        $this->pricings = PackagePricing::query()
            ->where('package_id', $this->package)
            ->where('available_capacity', '>', 1)
            ->active()
            ->orderBy('price')
            ->get();
        $this->pricingsHolder = $this->pricings->toArray();
    }
    #endregion

    #region Step 3 - Select Pricing & Guests
    public function addNewGuest()
    {
        $cheapestPricing = $this->pricings->first();
        $this->guests[] = ['name' => '', 'pricing' => $cheapestPricing->id, 'price' => $cheapestPricing->price, 'is_child' => false];
        $this->updatePrice(count($this->guests) - 1);
    }

    public function addNewChild()
    {
        $this->guests[] = ['name' => '', 'pricing' => 0, 'price' => $this->charge_per_child, 'is_child' => true];
        $this->updatePrice(0);
    }

    public function removeGuest($index)
    {
        unset($this->guests[$index]);
        $this->guests = array_values($this->guests);
        $this->updatePrice(count($this->guests) - 1);
    }

    public function updatePrice($index)
    {
        if (!$this->guests[$index]['is_child']) {
            $this->guests[$index]['price'] = $this->pricings->find($this->guests[$index]['pricing'])->price;
        }
        $this->totalPrice = collect($this->guests)->sum('price');
    }

    public function validateGuest()
    {
        $this->resetErrorBag();
        $data = $this->validate([
            'guests.*.name' => 'required|string|max:255',
            'guests.*.pricing' => 'required|integer',
            'guests.*.price' => 'required|numeric|min:1',
            'guests.*.is_child' => 'required|boolean',
            'tour' => 'required|exists:tours,id',
            'package' => 'required|exists:packages,id',
        ], attributes: [
            'guests.*.name' => __('Guest Name'),
            'guests.*.pricing' => __('Pricing'),
            'guests.*.price' => __('Price'),
            'guests.*.is_child' => __('Is Child'),
            'tour' => __('Tour'),
            'package' => __('Package'),
        ]);

        collect($data['guests'])
            ->filter(fn($guest) => !$guest['is_child'])
            ->each(function ($guest) {
                $arr = array_filter($this->pricingsHolder, function ($p) use ($guest) {
                    return $p['id'] == $guest['pricing'];
                });

                $index = array_keys($arr)[0];
                $pricing = $arr[$index];

                if ($pricing['available_capacity'] < 1) {
                    $this->addError(
                        'guests',
                        __('There is not enough capacity for the selected pricing of :packageName', [
                            'packageName' => $pricing['name'],
                        ])
                    );
                    $this->updatePricings();
                } else {
                    $this->pricingsHolder[$index]['available_capacity'] -= 1;
                }
            });

        if ($this->getErrorBag()->count() > 0) {
            return;
        }

        $this->currentStep++;
    }
    #endregion

    #region Step 4 - Confirm Booking
    public function saveBooking()
    {
        $this->booking = DB::transaction(function () {
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'package_id' => $this->package,
                'adult' => collect($this->guests)->where('is_child', false)->count(),
                'child' => collect($this->guests)->where('is_child', true)->count(),
                'total_price' => $this->totalPrice,
                'discount' => 0, // TODO Coupon
            ]);

            collect($this->guests)
                ->each(function ($guest) use ($booking) {
                    $booking->guests()->create([
                        'name' => $guest['name'],
                        'price' => $guest['price'],
                        'is_child' => $guest['is_child'],
                        'package_pricing_id' => $guest['pricing'],
                    ]);

                    $pricing = PackagePricing::find($guest['pricing']);
                    $pricing->decrement('available_capacity');
                });

            return $booking;
        });

        $this->currentStep++;
    }
    #endregion

    #region Step 5 - Payment

    #endregion

    #region Flow Control
    public function previousStep()
    {
        if ($this->currentStep == 4) {
            $this->updatePricings();
        }
        $this->currentStep--;
    }

    public function nextStep()
    {
        match ($this->currentStep) {
            1 => $this->updatedTour(),
            2 => $this->updatedPackage(),
            3 => $this->validateGuest(),
            4 => $this->saveBooking(),
            default => $this->currentStep++,
        };
    }
    #endregion
}
