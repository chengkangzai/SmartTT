<?php

namespace App\Providers;

use App\Http\Livewire\Booking\CreateBookingWizard;
use App\Http\Livewire\Booking\Steps\ChoosePackageStep;
use App\Http\Livewire\Booking\Steps\ChooseTourStep;
use App\Http\Livewire\Booking\Steps\ConfirmBookingDetailStep;
use App\Http\Livewire\Booking\Steps\CreatePaymentStep;
use App\Http\Livewire\Booking\Steps\RegisterBookingAndGuestStep;
use App\Http\Livewire\Booking\Steps\ShowBookingSuccessDetailStep;
use Carbon\Laravel\ServiceProvider;
use Livewire\Livewire;

class LivewireStepServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('create-booking-wizard', CreateBookingWizard::class);
        Livewire::component('choose-tour-step', ChooseTourStep::class);
        Livewire::component('choose-package-step', ChoosePackageStep::class);
        Livewire::component('register-booking-and-guest-step', RegisterBookingAndGuestStep::class);
        Livewire::component('confirm-booking-detail-step', ConfirmBookingDetailStep::class);
        Livewire::component('create-payment-step', CreatePaymentStep::class);
        Livewire::component('show-booking-success-detail-step', ShowBookingSuccessDetailStep::class);
    }
}
