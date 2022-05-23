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

    /** @var Tour $tour */
    public $tour;
    /** @var Package $package */
    public $package;
    /** @var Collection $pricings */
    public $pricings;
    public string $defaultCurrency;
    public int $totalPrice;
    /** @var Booking $booking */
    public $booking;

    public function render(): Factory|View|Application
    {
        $registerStep = $this->stateForStep('register-booking-and-guest-step');
        $tour = $this->stateForStep('choose-tour-step')['tour'];
        $package = $this->stateForStep('choose-package-step')['package'];

        $this->tour = Tour::find($tour);
        $this->package = Package::with('pricings')->find($package);
        $this->pricings = $this->package->pricings;

        $this->defaultCurrency = app(GeneralSetting::class)->default_currency;
        $this->totalPrice = $registerStep['totalPrice'];
        $this->guests = $registerStep['guests'];

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
