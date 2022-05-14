<?php

namespace App\Http\Livewire\Booking;

use App\Actions\Booking\CreatePaymentAction;
use App\Actions\Booking\Invoice\GenerateInvoiceAction;
use App\Actions\Booking\Invoice\GenerateReceiptAction;
use App\Actions\Booking\ValidateManualCardAction;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Settings\GeneralSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Stripe\SetupIntent;

class AddPaymentOnBooking extends Component
{
    #region Step 1
    public string $paymentType = Payment::TYPE_REMAINING;
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

    public int $paymentAmount = 0;
    public string $clientSecret = '';
    public Payment $payment;
    private SetupIntent $paymentIntent;
    #endregion

    public Booking $booking;
    public string $defaultCurrency;
    public int $currentStep = 1;

    protected $listeners = ['cardSetupConfirmed'];

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->paymentAmount = $booking->total_price - $booking->payment->filter(fn (Payment $payment) => $payment->status === Payment::STATUS_PAID)->sum('amount');
        $this->defaultCurrency = app(GeneralSetting::class)->default_currency;
        $this->paymentMethod = auth()->user()->hasRole('Customer') ? Payment::METHOD_STRIPE : 'manual';

        if ($this->paymentMethod === Payment::METHOD_STRIPE) {
            $this->clientSecret = auth()->user()->createSetupIntent()->client_secret;
        }
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.booking.add-payment-on-booking');
    }

    #region Step 1 Payment
    public function getReadyForPayment()
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

    public function cardSetupConfirmed(string $paymentMethod)
    {
        $user = auth()->user();
        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($paymentMethod);
        $user->invoiceFor('Booking(' . $this->paymentType . ') for Package #' . $this->booking->package_id . ' Of Tour '
            . $this->booking->package->tour->name, $this->paymentAmount * 100);

        $this->payment = Payment::create([
            'booking_id' => $this->booking->id,
            'amount' => $this->paymentAmount,
            'status' => Payment::STATUS_PENDING,
            'payment_type' => $this->paymentType,
            'payment_method' => $this->paymentMethod,
            'user_id' => auth()->id(),
        ]);

        $this->generateInvoice();
        $this->currentStep++;
    }

    public function recordManualPayment()
    {
        try {
            $this->payment = app(CreatePaymentAction::class)->execute($this->manualType, $this->booking, auth()->user(), [
                'payment_type' => $this->paymentType,
                'amount' => $this->paymentAmount,
                'card_holder_name' => $this->cardHolderName,
                'card_number' => $this->cardNumber,
                'card_expiry_date' => $this->cardExpiry,
                'card_cvc' => $this->cardCvc,
                'billing_name' => $this->billingName,
                'billing_phone' => $this->billingPhone,
                'paymentCashReceived' => $this->paymentCashReceived,
            ]);
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
    }

    #endregion

    public function finish()
    {
        session()->flash('success', __('Payment recorded successfully'));
        $this->redirectRoute('bookings.show', $this->booking);
    }
}
