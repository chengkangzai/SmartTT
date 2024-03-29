<?php

namespace App\Http\Livewire\Booking\Steps;

use App\Actions\Booking\Invoice\GenerateInvoiceAction;
use App\Actions\Booking\Invoice\GenerateReceiptAction;
use App\Actions\Booking\UpdateManualPaymentAction;
use App\Actions\Booking\ValidateManualCardAction;
use App\Models\Booking;
use App\Models\PackagePricing;
use App\Models\Payment;
use App\Models\Settings\BookingSetting;
use App\Models\Settings\GeneralSetting;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Spatie\LivewireWizard\Components\StepComponent;
use Stripe\SetupIntent;

class CreatePaymentStep extends StepComponent
{
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

    public string $stripePaymentMethod = '';

    public int $paymentAmount = 0;

    private SetupIntent $paymentIntent;

    /** @var Payment */
    public $payment;

    public string $defaultCurrency;

    public array $guests;

    public int $bookingId;

    /** @var Booking */
    public $booking;

    protected $listeners = ['cardSetupConfirmed'];

    public mixed $totalPrice;

    public function mount()
    {
        $this->paymentMethod = auth()->user()->hasRole('Customer') ? Payment::METHOD_STRIPE : 'manual';
        $this->defaultCurrency = app(GeneralSetting::class)->default_currency;

        $state = $this->state()->all();
        $this->bookingId = $state['confirm-booking-detail-step']['booking']['id'] ?? 1;
        $this->booking = Booking::find($this->bookingId);
        $this->guests = $state['register-booking-and-guest-step']['guests'] ?? [];
        $this->billingName = $state['register-billing-info-step']['billingName'] ?? '';
        $this->billingPhone = $state['register-billing-info-step']['billingPhone'] ?? '';
        $this->totalPrice = $state['register-booking-and-guest-step']['totalPrice'] ?? 0;

        $this->getReadyForPayment();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.booking.steps.create-payment-step');
    }

    private function getReadyForPayment(): void
    {
        $reservation_charge_per_pax = app(BookingSetting::class)->reservation_charge_per_pax * 100;

        $this->updateOrCreatePayment($this->totalPrice, $reservation_charge_per_pax);
        $this->paymentAmount = $this->payment->amount;

        $this->dispatchBrowserEventIfInStripeMode();
    }

    public function updatedPaymentMethod()
    {
        $this->getReadyForPayment();
    }

    public function updatedPaymentType()
    {
        $this->getReadyForPayment();
    }

    public function dispatchBrowserEventIfInStripeMode(): void
    {
        if ($this->paymentMethod == Payment::METHOD_STRIPE) {
            if (! isset($this->paymentIntent)) {
                $this->paymentIntent = auth()->user()->createSetupIntent();
            }

            $this->dispatchBrowserEvent('getReadyForPayment', [
                'clientSecret' => $this->paymentIntent->client_secret,
            ]);
        }
    }

    private function updateOrCreatePayment(mixed $totalPrice, int $reservation_charge_per_pax): void
    {
        $this->payment = Payment::updateOrCreate([
            'user_id' => auth()->id(),
            'booking_id' => $this->bookingId,
        ], [
            'status' => Payment::STATUS_PENDING,
            'amount' => $this->paymentType == Payment::TYPE_FULL
                ? $totalPrice
                : count($this->guests) * $reservation_charge_per_pax,
            'payment_method' => $this->paymentMethod == Payment::METHOD_STRIPE
                ? Payment::METHOD_STRIPE
                : $this->manualType,
            'payment_type' => $this->paymentType,
        ]);
    }

    public function cardSetupConfirmed(string $paymentMethod)
    {
        $user = auth()->user();
        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($paymentMethod);
        $user->invoiceFor(
            'Booking('.$this->paymentType.') for Package #'.$this->booking->package->id
            .' Of Tour '.$this->booking->package->tour->name,
            $this->paymentAmount * 100
        );

        $this->payment->update([
            'amount' => $this->paymentAmount,
            'payment_type' => $this->paymentType,
            'billing_name' => $this->billingName,
            'billing_phone' => $this->billingPhone,
            'status' => Payment::STATUS_PENDING,
        ]);

        $this->reduceAvailability();
        $this->generateInvoice();
        parent::nextStep();
    }

    public function recordManualPayment()
    {
        try {
            $this->resetErrorBag();
            $this->payment = app(UpdateManualPaymentAction::class)
                ->execute($this->payment, $this->manualType, auth()->user(), [
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

            parent::nextStep();
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
        $pricings = PackagePricing::whereIn('id', collect($this->guests)->pluck('pricing'))->get();
        collect($this->guests)
            ->filter(fn ($guest) => ! $guest['is_child'])
            ->each(function ($guest) use ($pricings) {
                $pricings->find($guest['pricing'])->decrement('available_capacity');
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

    /**
     * @throws Exception
     */
    public function nextStep()
    {
        if ($this->paymentMethod === 'manual') {
            $this->recordManualPayment();

            return;
        }

        throw new Exception("Payment method {$this->paymentMethod} not implemented");
    }
}
