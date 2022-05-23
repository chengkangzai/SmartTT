<?php

namespace App\Http\Livewire\Booking\Steps;

use App\Models\Package;
use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LivewireWizard\Components\StepComponent;

class ChooseTourStep extends StepComponent
{
    public int $tour = 0;

    public function render(): Factory|View|Application
    {
        return view('livewire.booking.steps.choose-tour-step',[
            'tours' => Tour::active()->get(['id', 'name']),
        ]);
    }

    public function updatedTour()
    {
        $this->nextStep();
    }

    public function nextStep()
    {
        $this->validate([
            'tour' => 'required|integer|exists:tours,id',
        ]);

        parent::nextStep();
    }
}
