<?php

namespace App\Http\Livewire\Booking;

use App\Http\Livewire\Booking\Steps\ChoosePackageStep;
use App\Http\Livewire\Booking\Steps\ChooseTourStep;
use App\Http\Livewire\Booking\Steps\ConfirmBookingDetailStep;
use App\Http\Livewire\Booking\Steps\CreatePaymentStep;
use App\Http\Livewire\Booking\Steps\RegisterBookingAndGuestStep;
use App\Http\Livewire\Booking\Steps\ShowBookingSuccessDetailStep;
use Spatie\LivewireWizard\Components\WizardComponent;

class CreateBookingWizard extends WizardComponent
{
    public function steps(): array
    {
        return [
            ChooseTourStep::class,
            ChoosePackageStep::class,
            RegisterBookingAndGuestStep::class,
            ConfirmBookingDetailStep::class,
            CreatePaymentStep::class,
            ShowBookingSuccessDetailStep::class,
        ];
    }
}
