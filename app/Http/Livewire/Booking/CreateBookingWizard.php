<?php

namespace App\Http\Livewire\Booking;

use App\Http\Livewire\Booking\Steps\ChoosePackageStep;
use App\Http\Livewire\Booking\Steps\ChooseTourStep;
use App\Http\Livewire\Booking\Steps\ConfirmBookingDetailStep;
use App\Http\Livewire\Booking\Steps\CreatePaymentStep;
use App\Http\Livewire\Booking\Steps\RegisterBillingInfoStep;
use App\Http\Livewire\Booking\Steps\RegisterBookingAndGuestStep;
use App\Http\Livewire\Booking\Steps\ShowBookingSuccessDetailStep;
use App\Models\Package;
use JetBrains\PhpStorm\ArrayShape;
use Spatie\LivewireWizard\Components\WizardComponent;

class CreateBookingWizard extends WizardComponent
{
    private ?string $packageId;

    private ?Package $package;

    public function steps(): array
    {
        return [
            ChooseTourStep::class,
            ChoosePackageStep::class,
            RegisterBookingAndGuestStep::class,
            RegisterBillingInfoStep::class,
            ConfirmBookingDetailStep::class,
            CreatePaymentStep::class,
            ShowBookingSuccessDetailStep::class,
        ];
    }

    public function mount(?string $packageId)
    {
        $this->packageId = $packageId;
        $this->package = Package::find($packageId);
        abort_if(!$this->package->is_active, 404);
        abort_if(!$this->package->tour->is_active, 404);
    }

    #[ArrayShape(['choose-tour-step' => "int[]", 'choose-package-step' => "int[]"])]
    public function initialState(): array
    {
        return [
            'choose-tour-step' => [
                'tour' => $this->package->tour_id ?? 0,
            ],
            'choose-package-step' => [
                'package' => (int)$this->packageId,
            ],
        ];
    }
}
