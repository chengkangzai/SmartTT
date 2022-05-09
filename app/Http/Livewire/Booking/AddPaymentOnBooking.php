<?php

namespace App\Http\Livewire\Booking;

use App\Actions\Booking\Invoice\GenerateInvoiceAction;
use App\Actions\Booking\Invoice\GenerateReceiptAction;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Settings\BookingSetting;
use App\Models\Settings\GeneralSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

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

    private array $validateCardRule = [
        'cardHolderName' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z ]+$/'],
        'cardNumber' => ['required', 'string', 'max:255', 'regex:/^[0-9]{16}$/'],
        'cardExpiry' => ['required', 'string', 'max:255', 'regex:/^[0-9]{2}\/[0-9]{2}$/'], // MM/YY
        'cardCvc' => ['required', 'string', 'max:255', 'regex:/^[0-9]{3,4}$/'],
    ];

    public int $paymentAmount = 0;
    public string $client_secret = '';
    public Payment $payment;
    #endregion

    public Booking $booking;
    public string $defaultCurrency;
    public int $reservation_charge_per_pax;
    public array $guests;
    public int $currentStep = 1;

    protected $listeners = ['cardSetupConfirmed'];

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->paymentAmount = $booking->total_price - $booking->payment->filter(fn (Payment $payment) => $payment->status === Payment::STATUS_PAID)->sum('amount');
        $this->defaultCurrency = app(GeneralSetting::class)->default_currency;
        $this->paymentMethod = auth()->user()->hasRole('Customer') ? Payment::METHOD_STRIPE : 'manual';
        $bookingSetting = app(BookingSetting::class);
        $this->reservation_charge_per_pax = $bookingSetting->reservation_charge_per_pax;
        $this->guests = $this->getGuests($bookingSetting);

        if ($this->paymentMethod === Payment::METHOD_STRIPE) {
            $this->client_secret = auth()->user()->createSetupIntent()->client_secret;
        }
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.booking.add-payment-on-booking');
    }

    #region Step 1 Payment
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
        $this->resetErrorBag();

        try {
            if ($this->manualType == Payment::METHOD_CARD) {
                $this->payment = Payment::create([
                    'booking_id' => $this->booking->id,
                    'status' => Payment::STATUS_PAID,
                    'payment_method' => $this->manualType,
                    'payment_type' => $this->paymentType,
                    'amount' => $this->paymentAmount,
                    'card_holder_name' => $this->cardHolderName,
                    'card_number' => $this->cardNumber,
                    'card_expiry_date' => $this->cardExpiry,
                    'card_cvc' => $this->cardCvc,
                    'user_id' => auth()->id(),
                ]);

                activity()
                    ->performedOn($this->booking)
                    ->causedBy(auth()->user())
                    ->log('Payment#' . $this->payment->id . '(Card) recorded for booking #' . $this->booking->id);
            }
            if ($this->manualType == Payment::METHOD_CASH) {
                $this->payment = Payment::create([
                    'booking_id' => $this->booking->id,
                    'status' => Payment::STATUS_PAID,
                    'payment_method' => $this->manualType,
                    'payment_type' => $this->paymentType,
                    'amount' => $this->paymentAmount,
                    'user_id' => auth()->id(),
                ]);

                activity()
                    ->performedOn($this->booking)
                    ->causedBy(auth()->user())
                    ->log('Payment#' . $this->payment->id . '(Card) recorded for booking #' . $this->booking->id);
            }
            $this->generateInvoice();
            $this->generateReceipt();
            $this->currentStep++;
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        }
    }

    public function validateCard(string $field)
    {
        $this->validate([
            $field => $this->validateCardRule[$field],
        ]);

        if ($this->getErrorBag()->isEmpty() && $field == 'cardExpiry') {
            $isBeforeNextMonth = Carbon::createFromFormat('m/y', $this->cardExpiry)->isBefore(Carbon::now());
            if ($isBeforeNextMonth) {
                $this->getErrorBag()->add('cardExpiry', __('The card is expired'));
            }
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

    private function getGuests(BookingSetting $bookingSetting): array
    {
        return $this->booking->guests->map(function ($guest) use ($bookingSetting) {
            return [
                'name' => $guest->name,
                'pricing' => $guest->package_pricing_id,
                'price' => $guest->packagePricing->price ?? $bookingSetting->charge_per_child,
            ];
        })->toArray();
    }
    #endregion

    public function finish()
    {
        session()->flash('success', __('Booking saved successfully'));
        $this->redirectRoute('bookings.index');
    }
}
