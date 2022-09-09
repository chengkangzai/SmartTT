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
        $tourId = $this->state()->forStep('choose-tour-step')['tour'] ?? 0;

        return view('livewire.booking.steps.choose-package-step', [
            'packages' => Package::where('tour_id', $tourId)->active()->orderBy('depart_time')->get(),
            'defaultCurrency' => app(GeneralSetting::class)->default_currency_symbol,
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
        ], attributes: [
            'package' => __('Package'),
        ]);

        parent::nextStep();
    }
}
