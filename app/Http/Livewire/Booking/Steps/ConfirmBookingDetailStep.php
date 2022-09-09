<?php

namespace App\Http\Livewire\Booking\Steps;

use App\Actions\Booking\StoreBookingAction;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Settings\GeneralSetting;
use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Spatie\LivewireWizard\Components\StepComponent;

class ConfirmBookingDetailStep extends StepComponent
{
    public array $guests;

    /** @var Tour */
    public $tour;
    /** @var Package */
    public $package;
    /** @var Collection */
    public $pricings;
    public string $defaultCurrency;
    public int $totalPrice;
    /** @var Booking */
    public $booking;

    public string $billingName = '';
    public string $billingPhone = '';

    public function render(): Factory|View|Application
    {
        $state = $this->state()->all();
        $registerStep = $state['register-booking-and-guest-step'] ?? [];
        $tour = $state['choose-tour-step']['tour'] ?? 0;
        $package = $state['choose-package-step']['package'] ?? 1;

        $this->tour = Tour::find($tour);
        $this->package = Package::with('packagePricing')->find($package);
        $this->pricings = $this->package?->packagePricing;

        $this->defaultCurrency = app(GeneralSetting::class)->default_currency_symbol;
        $this->totalPrice = $registerStep['totalPrice'] ?? 0;
        $this->guests = $registerStep['guests'] ?? [];

        $this->billingName = $state['register-billing-info-step']['billingName'] ?? '';
        $this->billingPhone = $state['register-billing-info-step']['billingPhone'] ?? '';

        return view('livewire.booking.steps.confirm-booking-detail-step');
    }

    public function nextStep()
    {
        $this->booking = app(StoreBookingAction::class)->execute(auth()->user(), [
            'package_id' => $this->package->id,
            'adult' => collect($this->guests)->where('is_child', false)->count(),
            'child' => collect($this->guests)->where('is_child', true)->count(),
            'total_price' => $this->totalPrice,
            'guests' => $this->guests,
        ]);

        parent::nextStep();
    }
}
