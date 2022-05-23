<?php

namespace App\Http\Livewire\Booking\Steps;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Spatie\LivewireWizard\Components\StepComponent;

class ShowBookingSuccessDetailStep extends StepComponent
{
    public string $paymentMethod;
    public string $defaultCurrency;
    public string $paymentAmount;
    public $payment;

    public function mount()
    {
        dd($this->allStepsState());
        $this->paymentMethod = auth()->user()->hasRole('Customer') ? Payment::METHOD_STRIPE : 'manual';
        $this->defaultCurrency = app(GeneralSetting::class)->default_currency;
        $this->paymentAmount = $this->stateForStep('confirm-booking-detail-step')['booking']['total_amount'];
        $this->payment = $this->stateForStep('confirm-booking-detail-step')['booking']['payment'];
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.booking.steps.show-booking-success-detail-step');
    }
}
