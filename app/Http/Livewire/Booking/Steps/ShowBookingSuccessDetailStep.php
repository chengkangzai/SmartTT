<?php

namespace App\Http\Livewire\Booking\Steps;

use App\Filament\Resources\BookingResource;
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

    /** @var Payment */
    public $payment;

    public function mount()
    {
        $this->defaultCurrency = app(GeneralSetting::class)->default_currency;

        $previousState = $this->state()->forStep('create-payment-step');
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
        $this->redirect(BookingResource::getUrl('view', ['record' => $this->payment->booking_id]));
    }
}
