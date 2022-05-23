<?php

namespace App\Http\Livewire\Booking\Steps;

use App\Models\Payment;
use App\Models\Settings\GeneralSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Spatie\LivewireWizard\Components\StepComponent;

class ShowBookingSuccessDetailStep extends StepComponent
{
    public string $paymentMethod;
    public string $defaultCurrency;
    public string $paymentAmount;
    /** @var Payment $payment */
    public $payment;

    public function mount()
    {
        $this->defaultCurrency = app(GeneralSetting::class)->default_currency;

        $previousState = $this->stateForStep('create-payment-step');
        $this->paymentMethod = $previousState['paymentMethod'];
        $this->paymentAmount = $previousState['paymentAmount'];
        $this->payment = Payment::findOrFail($previousState['payment']['id']);
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.booking.steps.show-booking-success-detail-step');
    }

    public function nextStep()
    {
        $this->redirectRoute('bookings.show', $this->payment->booking);
    }
}
