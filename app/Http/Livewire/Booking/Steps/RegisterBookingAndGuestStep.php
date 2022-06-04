<?php

namespace App\Http\Livewire\Booking\Steps;

use App\Actions\Booking\ValidateBookingGuestAction;
use App\Models\PackagePricing;
use App\Models\Settings\BookingSetting;
use App\Models\Settings\GeneralSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Spatie\LivewireWizard\Components\StepComponent;

class RegisterBookingAndGuestStep extends StepComponent
{
    public int $package;
    /** @var PackagePricing[]|Collection */
    public $pricings;
    public int $pricing = 0;
    public array $pricingsHolder;
    public int $totalPrice = 0;

    public array $guests = [];
    public int $bookingId = 0;

    public mixed $defaultCurrency;
    public int $charge_per_child;

    public function mount()
    {
        $this->package = $this->state()->forStep('choose-package-step')['package'] ?? 1;
        $this->updatePricings();
        $this->defaultCurrency = app(GeneralSetting::class)->default_currency_symbol;
        $this->charge_per_child = app(BookingSetting::class)->charge_per_child;
        if (count($this->guests) == 0) {
            $this->addNewGuest();
        } else {
            $this->updatePrice(0);
        }
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.booking.steps.register-booking-and-guest-step');
    }

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
        if (! $this->guests[$index]['is_child']) {
            $this->guests[$index]['price'] = $this->pricings->find($this->guests[$index]['pricing'])->price;
        }
        $this->totalPrice = collect($this->guests)->sum('price');
    }

    public function nextStep()
    {
        try {
            $this->resetErrorBag();
            app(ValidateBookingGuestAction::class)->execute($this->pricingsHolder, [
                'guests' => $this->guests,
            ]);
            parent::nextStep();
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            $this->updatePricings();

            return;
        }
    }

    private function updatePricings(): void
    {
        $this->pricings = PackagePricing::query()
            ->where('package_id', $this->package)
            ->available()
            ->active()
            ->orderBy('price')
            ->get();
        $this->pricingsHolder = $this->pricings->toArray();
    }
}
