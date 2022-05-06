<?php

namespace App\Http\Livewire\Booking;

use function activity;
use function app;
use App\Models\Booking;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\Payment;
use App\Models\Settings\BookingSetting;
use App\Models\Settings\GeneralSetting;
use App\Models\Tour;
use function array_filter;
use function array_keys;
use function auth;
use function collect;
use function count;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
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
    private Booking $booking;
    public int $bookingId;
    #endregion
    #region Step 5
    public string $paymentType = 'full';
    public string $paymentMethod;
    public string $manualType = 'card';
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

    public string $stripePaymentMethod = '';
    public int $paymentAmount = 0;
    private SetupIntent $paymentIntent;
    #endregion

    public int $currentStep = 1;
    public string $defaultCurrency;
    public int $charge_per_child;

    protected $listeners = ['cardSetupConfirmed'];

    public function mount()
    {
        $this->tours = Tour::active()->get(['id', 'name']);
        $this->defaultCurrency = app(GeneralSetting::class)->default_currency;
        $this->charge_per_child = app(BookingSetting::class)->charge_per_child;
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
        if ($this->tour == 0) {
            $this->addError('tour', __('Please select a tour'));

            return;
        }
        if ($this->getErrorBag()->has('tour')) {
            $this->resetErrorBag();
        }
        $this->currentStep++;
        $this->packages = Package::where('tour_id', $this->tour)->active()->get();
    }
    #endregion

    #region Step 2 - Select Package
    public function updatedPackage()
    {
        if ($this->package == 0) {
            $this->addError('package', __('Please select a package'));

            return;
        }
        if ($this->getErrorBag()->has('package')) {
            $this->resetErrorBag();
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
            ->where('available_capacity', '>', 1)
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
        $data = $this->validate([
            'guests.*.name' => 'required|string|max:255',
            'guests.*.pricing' => 'required|integer',
            'guests.*.price' => 'required|numeric|min:1',
            'guests.*.is_child' => 'required|boolean',
            'tour' => 'required|exists:tours,id',
            'package' => 'required|exists:packages,id',
        ], attributes: [
            'guests.*.name' => __('Guest Name'),
            'guests.*.pricing' => __('Pricing'),
            'guests.*.price' => __('Price'),
            'guests.*.is_child' => __('Is Child'),
            'tour' => __('Tour'),
            'package' => __('Package'),
        ]);

        collect($data['guests'])
            ->filter(fn ($guest) => ! $guest['is_child'])
            ->each(function ($guest) {
                $arr = array_filter($this->pricingsHolder, function ($p) use ($guest) {
                    return $p['id'] == $guest['pricing'];
                });

                $index = array_keys($arr)[0];
                $pricing = $arr[$index];

                if ($pricing['available_capacity'] < 1) {
                    $this->addError(
                        'guests',
                        __('There is not enough capacity for the selected pricing of :packageName', [
                            'packageName' => $pricing['name'],
                        ])
                    );
                    $this->updatePricings();
                } else {
                    $this->pricingsHolder[$index]['available_capacity'] -= 1;
                }
            });

        if ($this->getErrorBag()->count() > 0) {
            return;
        }

        $this->currentStep++;
    }
    #endregion

    #region Step 4 - Confirm Booking
    public function saveBooking()
    {
        if (! isset($this->booking)) {
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'package_id' => $this->package,
                'adult' => collect($this->guests)->where('is_child', false)->count(),
                'child' => collect($this->guests)->where('is_child', true)->count(),
                'total_price' => $this->totalPrice,
                'discount' => 0, // TODO Coupon
            ]);

            collect($this->guests)
                ->each(function ($guest) use ($booking) {
                    $booking->guests()->create([
                        'name' => $guest['name'],
                        'price' => $guest['price'],
                        'is_child' => $guest['is_child'],
                        'package_pricing_id' => $guest['pricing'],
                    ]);
                });

            $this->booking = $booking;
            $this->bookingId = $this->booking->id;
        }
        $this->getReadyForPayment();
        $this->currentStep++;
    }
    #endregion

    #region Step 5 - Payment
    public function getReadyForPayment()
    {
        $payment = Payment::updateOrCreate([
            'user_id' => auth()->id(),
            'booking_id' => $this->bookingId,
        ], [
            'status' => Payment::STATUS_PENDING,
            'amount' => $this->paymentType == 'full' ? $this->totalPrice : count($this->guests) * 200,
            'payment_method' => $this->paymentMethod,
            'payment_type' => $this->paymentType,
        ]);

        $this->paymentAmount = $payment->amount;

        if ($this->paymentMethod == 'stripe') {
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
        $this->currentStep++;
    }

    public function recordManualPayment()
    {
        $payment = Payment::whereBookingId($this->bookingId)->latest()->first();
        $this->resetErrorBag();

        if ($this->manualType == 'card') {
            $this->validate($this->validateCardRule);
            $payment->update([
                'status' => Payment::STATUS_PAID,
                'amount' => $this->paymentAmount,
                'payment_method' => $this->paymentMethod,
                'payment_type' => $this->paymentType,
                'booking_id' => $this->bookingId,
                'card_holder_name' => $this->cardHolderName,
                'card_number' => $this->cardNumber,
                'card_expiry_date' => $this->cardExpiry,
                'card_cvc' => $this->cardCvc,
                'user_id' => auth()->id(),
            ]);
            activity()
                ->performedOn($payment)
                ->log('Payment#' . $payment->id . '(Card) recorded for booking #' . $this->bookingId . ' by ' . auth()->user()->name);
        } else {
            if (! $this->paymentCashReceived) {
                $this->getErrorBag()->add('paymentCashReceived', __('Please confirm that you have received the cash.'));

                return;
            }
            $payment->update([
                'status' => Payment::STATUS_PAID,
                'amount' => $this->paymentAmount,
                'payment_method' => $this->paymentMethod,
                'payment_type' => $this->paymentType,
                'user_id' => auth()->id(),
            ]);
            activity()
                ->performedOn($payment)
                ->log('Payment#' . $payment->id . '(Cash) recorded for booking #' . $this->bookingId . ' by ' . auth()->user()->name);
        }

        $this->reduceAvailability();
        $this->currentStep++;
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

    private function reduceAvailability(): void
    {
        collect($this->guests)
            ->each(function ($guest) {
                $this->pricings->find($guest['pricing'])->decrement('available_capacity');
            });
    }
    #endregion

    #region Flow Control
    public function previousStep()
    {
        if ($this->currentStep == 4) {
            $this->updatePricings();
        }
        $this->currentStep--;
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
    private function finish()
    {
        session()->flash('success', __('Booking saved successfully'));
        $this->redirectRoute('bookings.index');
    }
}
