<?php

namespace App\Http\Livewire\Booking\Steps;

use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Spatie\LivewireWizard\Components\StepComponent;

class ChooseTourStep extends StepComponent
{
    public int $tour = 0;

    public function render(): Factory|View|Application
    {
        return view('livewire.booking.steps.choose-tour-step', [
            'tours' => Tour::active()->whereHas('activePackages.activePricings')->get(['id', 'name']),
        ]);
    }

    public function updatedTour()
    {
        $this->nextStep();
    }

    public function nextStep()
    {
        $this->validate(rules:[
            'tour' => 'required|integer|exists:tours,id',
        ],attributes: [
            'tour' => __('Tour'),
        ]);

        parent::nextStep();
    }
}
