<?php

namespace App\Http\Livewire\Booking\Steps;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Spatie\LivewireWizard\Components\StepComponent;

class RegisterBillingInfoStep extends StepComponent
{
    public string $billingName = '';
    public string $billingPhone = '';
    public array $validateBillingRule = [
        'billingName' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z ]+$/'],
        'billingPhone' => ['required', 'string', 'max:255', 'regex:/^[0-9]{10,13}$/'],
    ];
    public array $guests;

    public function mount()
    {
        $state = $this->state()->all();
        $this->guests = $state['register-booking-and-guest-step']['guests'];
        $this->billingName = $this->guests[0]['name'];
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.booking.steps.register-billing-info-step');
    }

    public function validateBilling(string $field)
    {
        $this->validate([
            $field => $this->validateBillingRule[$field],
        ], [], [
            'billingName' => __('Billing Name'),
            'billingPhone' => __('Billing Phone'),
        ]);
    }

    public function nextStep()
    {
        $this->validate([
            'billingName' => $this->validateBillingRule['billingName'],
            'billingPhone' => $this->validateBillingRule['billingPhone'],
        ], [], [
            'billingName' => __('Billing Name'),
            'billingPhone' => __('Billing Phone'),
        ]);

        parent::nextStep();
    }
}
