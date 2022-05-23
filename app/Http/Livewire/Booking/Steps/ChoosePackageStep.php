<?php

namespace App\Http\Livewire\Booking\Steps;

use App\Models\Package;
use App\Models\Settings\GeneralSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Spatie\LivewireWizard\Components\StepComponent;

class ChoosePackageStep extends StepComponent
{
    public int $package = 0;

    public function render(): Factory|View|Application
    {
        //TODO show depart place
        $tourId = $this->stateForStep('choose-tour-step')['tour'];

        return view('livewire.booking.steps.choose-package-step', [
            'packages' => Package::where('tour_id', $tourId)->active()->get(),
            'defaultCurrency' => app(GeneralSetting::class)->default_currency,
        ]);
    }

    public function updatedPackage()
    {
        $this->nextStep();
    }

    public function nextStep()
    {
        $this->validate([
            'package' => 'required|integer|exists:packages,id',
        ]);

        parent::nextStep();
    }
}
