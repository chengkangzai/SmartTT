<?php

use App\Http\Livewire\Booking\CreateBookingCard;
use App\Models\Booking;
use App\Models\PackagePricing;
use App\Models\Payment;
use App\Models\Settings\BookingSetting;
use App\Models\Settings\GeneralSetting;
use App\Models\Tour;
use App\Models\User;
use Database\Seeders\AirlineSeeder;
use Database\Seeders\AirportSeeder;
use Database\Seeders\BookingSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\TourSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
        CountrySeeder::class,
        TourSeeder::class,
        AirlineSeeder::class,
        AirportSeeder::class,
        FlightSeeder::class,
        PackageSeeder::class,
        BookingSeeder::class,
    ]);
});

it('should be mountable', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(CreateBookingCard::class)
        ->assertSuccessful();
});

it('should be create booking as customer', function () {
    $selectedTour = Tour::with(['packages', 'packages.pricings'])->active()
        ->get()
        ->filter(fn ($tour) => $tour->packages->count() > 0)
        ->filter(fn ($tour) => $tour->packages->filter(fn ($package) => $package->pricings->count() > 0)->count() > 0)
        ->first();
    $selectedPackage = $selectedTour->packages->first();
    $packagePricing = PackagePricing::wherePackageId($selectedPackage->id)->first();
    $livewire = Livewire::actingAs(User::factory()->customer()->create())
        ->test(CreateBookingCard::class)
        ->assertViewIs('livewire.booking.create-booking-card')
        ->assertSet('tours', Tour::active()->get(['id', 'name']))
        ->assertSet('defaultCurrency', app(GeneralSetting::class)->default_currency)
        ->assertSet('charge_per_child', app(BookingSetting::class)->charge_per_child)
        ->assertSet('reservation_charge_per_pax', app(BookingSetting::class)->reservation_charge_per_pax)
        ->assertSet('paymentMethod', 'stripe')
        ->call('nextStep')
        ->assertHasErrors('tour')
        ->assertSet('currentStep', 1)
        ->set('tour', $selectedTour->id)
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        ->assertHasNoErrors('tour')
        ->assertHasErrors('package')
        ->set('package', $selectedPackage->id)
        ->call('nextStep')
        ->assertSet('currentStep', 3)
        ->assertHasNoErrors('package')
        ->call('previousStep')
        ->assertSet('currentStep', 2)
        ->call('nextStep')
        ->assertSet('currentStep', 3)
        ->assertHasNoErrors('package')
        ->call('addNewChild')
        ->call('addNewChild')
        ->call('removeGuest', 2)
        ->call('nextStep')
        ->assertHasErrors('guests.0.name')
        ->set('guests.0.name', 'John')
        ->set('guests.0.pricing', $packagePricing->id)
        ->set('guests.1.name', 'John Doe')
        ->call('nextStep')
        ->assertSet('currentStep', 4)
        ->assertSet('bookingId', 0)
        ->call('nextStep')
        ->assertSet('currentStep', 5)
        ->assertSet('bookingId', Booking::latest()->first()->id)
        ->assertDispatchedBrowserEvent('getReadyForPayment')
        ->assertSuccessful();

    $booking = Booking::find($livewire->bookingId);
    expect($booking->package_id)->toBe($selectedPackage->id);
    expect($booking->guests->count())->toBe(2);
    expect($booking->guests->first()->name)->toBe('John');
    expect($booking->guests->first()->is_child)->toBeFalsy();
    expect($booking->guests->first()->package_pricing_id)->toBe($packagePricing->id);
    expect($booking->guests->last()->name)->toBe('John Doe');
    expect($booking->guests->last()->is_child)->toBeTruthy();
    expect($booking->payment()->count())->toBe(1);
});

it('should be create booking as staff', function () {
    $reservationCharge = app(BookingSetting::class)->reservation_charge_per_pax;

    $selectedTour = Tour::with(['packages', 'packages.pricings'])->active()
        ->get()
        ->filter(fn ($tour) => $tour->packages->count() > 0)
        ->filter(fn ($tour) => $tour->packages->filter(fn ($package) => $package->pricings->count() > 0)->count() > 0)
        ->first();
    $selectedPackage = $selectedTour->packages->first();
    $packagePricing = PackagePricing::wherePackageId($selectedPackage->id)->first();
    $livewire = Livewire::actingAs(User::factory()->staff()->create())
        ->test(CreateBookingCard::class)
        ->assertViewIs('livewire.booking.create-booking-card')
        ->assertSet('tours', Tour::active()->get(['id', 'name']))
        ->assertSet('defaultCurrency', app(GeneralSetting::class)->default_currency)
        ->assertSet('charge_per_child', app(BookingSetting::class)->charge_per_child)
        ->assertSet('reservation_charge_per_pax', $reservationCharge)
        ->assertSet('paymentMethod', 'manual')
        ->call('nextStep')
        ->assertHasErrors('tour')
        ->assertSet('currentStep', 1)
        ->set('tour', $selectedTour->id)
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        ->assertHasNoErrors('tour')
        ->assertHasErrors('package')
        ->set('package', $selectedPackage->id)
        ->call('nextStep')
        ->assertSet('currentStep', 3)
        ->assertHasNoErrors('package')
        ->call('previousStep')
        ->assertSet('currentStep', 2)
        ->call('nextStep')
        ->assertSet('currentStep', 3)
        ->assertHasNoErrors('package')
        ->call('addNewChild')
        ->call('addNewChild')
        ->call('removeGuest', 2)
        ->call('nextStep')
        ->assertHasErrors('guests.0.name')
        ->set('guests.0.name', 'John')
        ->set('guests.0.pricing', $packagePricing->id)
        ->set('guests.1.name', 'John Doe')
        ->call('nextStep')
        ->assertSet('currentStep', 4)
        ->call('previousStep')
        ->assertSet('currentStep', 3)
        ->call('nextStep')
        ->assertSet('currentStep', 4)
        ->assertSet('bookingId', 0)
        ->call('nextStep')
        ->assertSet('currentStep', 5)
        ->call('previousStep')
        ->assertSet('bookingId', Booking::latest()->first()->id)
        ->assertSuccessful();

    $booking = Booking::find($livewire->bookingId);
    expect($booking->package_id)->toBe($selectedPackage->id);
    expect($booking->guests->count())->toBe(2);
    expect($booking->guests->first()->name)->toBe('John');
    expect($booking->guests->first()->is_child)->toBeFalsy();
    expect($booking->guests->first()->package_pricing_id)->toBe($packagePricing->id);
    expect($booking->guests->last()->name)->toBe('John Doe');
    expect($booking->guests->last()->is_child)->toBeTruthy();
    expect($booking->payment()->count())->toBe(1);


    $livewire->call('recordManualPayment')
        ->assertHasErrors([
            'billing_phone', 'card_holder_name', 'card_number', 'card_expiry_date', 'card_cvc', 'billing_phone',
        ])
        ->assertSet('paymentAmount', $booking->total_price)
        ->set('paymentType', Payment::TYPE_RESERVATION)
        ->assertSet('paymentAmount', $reservationCharge * 2)
        ->set('manualType', Payment::METHOD_CARD)
        ->call('validateCard', 'cardHolderName')
        ->assertHasErrors('cardHolderName')
        ->call('validateCard', 'cardNumber')
        ->assertHasErrors('cardNumber')
        ->call('validateCard', 'cardExpiry')
        ->assertHasErrors('cardExpiry')
        ->call('validateCard', 'cardCvc')
        ->assertHasErrors('cardCvc')
        ->set('cardHolderName', 'John Doe')
        ->call('validateCard', 'cardHolderName')
        ->assertHasNoErrors()
        ->call('validateBilling', 'billingName')
        ->assertHasNoErrors('billingName')
        ->call('validateBilling', 'billingPhone')
        ->assertHasErrors('billingPhone')
        ->set('manualType', Payment::METHOD_CASH)
        ->set('paymentCashReceived', 1)
        ->set('billingPhone', '+60123456789')
        ->call('recordManualPayment')
        ->assertSet('currentStep', 5)
        ->assertHasNoErrors();

    expect($booking->payment()->count())->toBe(1);
    $payment = $booking->payment()->first();
    expect($payment->amount)->toBe($reservationCharge * 2);
    expect($payment->payment_method)->toBe(Payment::METHOD_CASH);
    expect($payment->payment_type)->toBe(Payment::TYPE_RESERVATION);
    expect($payment->status)->toBe(Payment::STATUS_PAID);
    expect($payment->getFirstMediaUrl('invoices'))->not->toBeEmpty();
    expect($payment->getFirstMediaUrl('receipts'))->not->toBeEmpty();

    $livewire
        ->call('nextStep')
        ->call('nextStep')
        ->assertSessionHas('success')
        ->assertRedirect(route('bookings.show', $booking->id));
});
