<?php

namespace App\Http\Livewire\Booking;

use App\Actions\Booking\Invoice\GenerateInvoiceAction;
use App\Actions\Booking\Invoice\GenerateReceiptAction;
use App\Actions\Booking\StoreBookingAction;
use App\Actions\Booking\UpdateManualPaymentAction;
use App\Actions\Booking\ValidateBookingGuestAction;
use App\Actions\Booking\ValidateManualCardAction;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\Payment;
use App\Models\Settings\BookingSetting;
use App\Models\Settings\GeneralSetting;
use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Stripe\SetupIntent;

class CreateBookingCard extends Component
{
    #region Step 1
    public Collection $tours;
    public int $tour = 0;
    #endregion
    #region Step 2
    public Collection $packages;
    public int $package = 0;
    #endregion
    #region Step 3
    public Collection $pricings;
    public int $pricing = 0;
    public array $pricingsHolder;
    public int $totalPrice = 0;
    #endregion
    #region Step 4
    public array $guests = [];
    public int $bookingId = 0;
    #endregion
    #region Step 5
    public string $paymentType = Payment::TYPE_FULL;
    public string $paymentMethod;
    public string $manualType = Payment::METHOD_CARD;
    public bool $paymentCashReceived = false;

    public string $cardHolderName = '';
    public string $cardNumber = '';
    public string $cardExpiry = '';
    public string $cardCvc = '';

    public string $billingName = '';
    public string $billingPhone = '';
    public array $validateBillingRule = [
        'billingName' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z ]+$/'],
        'billingPhone' => ['required', 'string', 'max:255', 'regex:/^[0-9]{10,13}$/'],
    ];

    public string $stripePaymentMethod = '';
    public int $paymentAmount = 0;
    private SetupIntent $paymentIntent;

    public Payment $payment;
    #endregion

    public int $currentStep = 1;
    public string $defaultCurrency;
    public int $charge_per_child;
    public int $reservation_charge_per_pax;

    protected $listeners = ['cardSetupConfirmed'];

    public function mount()
    {
        $this->tours = Tour::active()->get(['id', 'name']);
        $this->defaultCurrency = app(GeneralSetting::class)->default_currency;
        $bookingSetting = app(BookingSetting::class);
        $this->charge_per_child = $bookingSetting->charge_per_child;
        $this->reservation_charge_per_pax = $bookingSetting->reservation_charge_per_pax;
        $this->paymentMethod = auth()->user()->hasRole('Customer') ? 'stripe' : 'manual';
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.booking.create-booking-card', [
            'tours' => $this->tours,
        ]);
    }

    #region Step 1 - Select Tour
    public function updatedTour()
    {
        $this->resetErrorBag();
        if ($this->tour == 0) {
            $this->addError('tour', __('Please select a tour'));

            return;
        }
        $this->currentStep++;
        $this->packages = Package::where('tour_id', $this->tour)->active()->get();
    }
    #endregion

    #region Step 2 - Select Package
    public function updatedPackage()
    {
        $this->resetErrorBag();
        if ($this->package == 0) {
            $this->addError('package', __('Please select a package'));

            return;
        }
        $this->currentStep++;
        $this->updatePricings();
        if (count($this->guests) == 0) {
            $this->addNewGuest();
        } else {
            $this->updatePrice(0);
        }
    }

    private function updatePricings(): void
    {
        $this->pricings = PackagePricing::query()
            ->where('package_id', $this->package)
            ->available()
            ->active()
            ->orderBy('price')
            ->get();
        $this->pricingsHolder = $this->pricings->toArray();
    }
    #endregion

    #region Step 3 - Select Pricing & Guests
    public function addNewGuest()
    {
        $cheapestPricing = $this->pricings->first();
        $this->guests[] = ['name' => '', 'pricing' => $cheapestPricing->id, 'price' => $cheapestPricing->price, 'is_child' => false];
        $this->updatePrice(count($this->guests) - 1);
    }

    public function addNewChild()
    {
        $this->guests[] = ['name' => '', 'pricing' => 0, 'price' => $this->charge_per_child, 'is_child' => true];
        $this->updatePrice(0);
    }

    public function removeGuest($index)
    {
        unset($this->guests[$index]);
        $this->guests = array_values($this->guests);
        $this->updatePrice(count($this->guests) - 1);
    }

    public function updatePrice($index)
    {
        if (! $this->guests[$index]['is_child']) {
            $this->guests[$index]['price'] = $this->pricings->find($this->guests[$index]['pricing'])->price;
        }
        $this->totalPrice = collect($this->guests)->sum('price');
    }

    public function validateGuest()
    {
        $this->resetErrorBag();

        try {
            app(ValidateBookingGuestAction::class)->execute($this->pricingsHolder, [
                'guests' => $this->guests,
            ]);
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            $this->updatePricings();

            return;
        }

        $this->currentStep++;
    }
    #endregion

    #region Step 4 - Confirm Booking
    public function saveBooking()
    {
        if ($this->bookingId == 0) {
            $booking = app(StoreBookingAction::class)->execute(auth()->user(), [
                'package_id' => $this->package,
                'adult' => collect($this->guests)->where('is_child', false)->count(),
                'child' => collect($this->guests)->where('is_child', true)->count(),
                'total_price' => $this->totalPrice,
                'guests' => $this->guests,
            ]);

            $this->bookingId = $booking->id;
            $this->totalPrice = $booking->total_price;
        }

        $this->getReadyForPayment();
        $this->currentStep++;
    }
    #endregion

    #region Step 5 - Payment
    public function getReadyForPayment()
    {
        $this->payment = Payment::updateOrCreate([
            'user_id' => auth()->id(),
            'booking_id' => $this->bookingId,
        ], [
            'status' => Payment::STATUS_PENDING,
            'amount' => $this->paymentType == Payment::TYPE_FULL
                ? $this->totalPrice
                : count($this->guests) * $this->reservation_charge_per_pax,
            'payment_method' => $this->paymentMethod == Payment::METHOD_STRIPE
                ? Payment::METHOD_STRIPE
                : $this->manualType,
            'payment_type' => $this->paymentType,
        ]);

        $this->paymentAmount = $this->payment->amount;
        $this->billingName = $this->guests[0]['name'];

        if ($this->paymentMethod == Payment::METHOD_STRIPE) {
            if (! isset($this->paymentIntent)) {
                $this->paymentIntent = auth()->user()->createSetupIntent();
            }

            $this->dispatchBrowserEvent('getReadyForPayment', [
                'clientSecret' => $this->paymentIntent->client_secret,
            ]);
        }
    }

    public function updatedPaymentMethod()
    {
        $this->getReadyForPayment();
    }

    public function updatedPaymentType()
    {
        $this->getReadyForPayment();
    }

    public function cardSetupConfirmed(string $paymentMethod)
    {
        $user = auth()->user();
        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($paymentMethod);
        $user->invoiceFor('Booking(' . $this->paymentType . ') for Package #' . $this->package . ' Of Tour '
            . $this->tours->find($this->tour)->first()->name, $this->paymentAmount * 100);

        $this->reduceAvailability();
        $this->generateInvoice();
        $this->currentStep++;
    }

    public function recordManualPayment()
    {
        $payment = Payment::whereBookingId($this->bookingId)->latest()->first();
        $this->resetErrorBag();

        try {
            $this->payment = app(UpdateManualPaymentAction::class)
                ->execute($payment, $this->manualType, auth()->user(), [
                    'amount' => $this->paymentAmount,
                    'payment_type' => $this->paymentType,
                    'card_holder_name' => $this->cardHolderName,
                    'card_number' => $this->cardNumber,
                    'card_expiry_date' => $this->cardExpiry,
                    'card_cvc' => $this->cardCvc,
                    'paymentCashReceived' => $this->paymentCashReceived,
                    'billing_name' => $this->billingName,
                    'billing_phone' => $this->billingPhone,
                ]);
            $this->reduceAvailability();
            $this->generateInvoice();
            $this->generateReceipt();
            $this->currentStep++;
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        }
    }

    public function validateCard(string $field)
    {
        try {
            $this->resetErrorBag();
            app(ValidateManualCardAction::class)->execute($field, $this->{$field});
            if ($field == 'cardHolderName') {
                $this->billingName = $this->cardHolderName;
            }
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        }
    }

    private function reduceAvailability(): void
    {
        collect($this->guests)
            ->filter(fn ($guest) => ! $guest['is_child'])
            ->each(function ($guest) {
                $this->pricings->find($guest['pricing'])->decrement('available_capacity');
            });
    }

    private function generateReceipt()
    {
        $this->payment = app(GenerateReceiptAction::class)->execute($this->payment);
    }

    private function generateInvoice()
    {
        $this->payment = app(GenerateInvoiceAction::class)->execute($this->payment);
    }

    public function validateBilling(string $field)
    {
        $this->validate([
            $field => $this->validateBillingRule[$field],
        ]);
        $this->getReadyForPayment();
    }
    #endregion

    #region Step 6 - Finish
    private function finish()
    {
        session()->flash('success', __('Booking saved successfully'));
        $this->redirectRoute('bookings.index');
    }
    #endregion

    #region Flow Control
    public function previousStep()
    {
        if ($this->currentStep == 4) {
            $this->updatePricings();
        }
        $this->currentStep--;
        $this->resetErrorBag();
    }

    public function nextStep()
    {
        match ($this->currentStep) {
            1 => $this->updatedTour(),
            2 => $this->updatedPackage(),
            3 => $this->validateGuest(),
            4 => $this->saveBooking(),
            6 => $this->finish(),
            default => $this->currentStep++,
        };
    }
    #endregion
}
