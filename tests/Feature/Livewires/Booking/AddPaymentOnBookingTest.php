<?php

use App\Filament\Resources\BookingResource;
use App\Http\Livewire\Booking\AddPaymentOnBooking;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Settings\GeneralSetting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

use function Pest\Laravel\seed;

beforeEach(function () {
    seed([
        DatabaseSeeder::class,
    ]);
});

it('should be mountable', function () {
    Livewire::actingAs(User::factory()->customer()->create())
        ->test(AddPaymentOnBooking::class)
        ->assertSuccessful();
});

it('should add payment as customer', function () {
    $user = User::factory()->customer()->create();
    $booking = Booking::factory()->create([
        'user_id' => $user->id,
    ]);
    Livewire::actingAs($user)
        ->test(AddPaymentOnBooking::class, [
            'booking' => $booking,
        ])
        ->assertViewIs('livewire.booking.add-payment-on-booking')
        ->assertSet('booking', $booking)
        ->assertSet('paymentMethod', Payment::METHOD_STRIPE)
        ->assertSet('paymentAmount', $booking->getRemaining())
        ->assertSet('defaultCurrency', app(GeneralSetting::class)->default_currency)
        ->call('getReadyForPayment')
        ->assertDispatchedBrowserEvent('getReadyForPayment');
});

it('should add payment as Admin', function () {
    $user = User::factory()->staff()->create();
    $booking = Booking::factory()->create([
        'user_id' => $user->id,
    ]);
    $livewire = Livewire::actingAs($user)
        ->test(AddPaymentOnBooking::class, [
            'booking' => $booking,
        ])
        ->assertViewIs('livewire.booking.add-payment-on-booking')
        ->assertSet('booking', $booking)
        ->assertSet('paymentMethod', 'manual')
        ->assertSet('paymentAmount', $booking->getRemaining())
        ->assertSet('defaultCurrency', app(GeneralSetting::class)->default_currency);

    $livewire->call('getReadyForPayment')
        ->call('recordManualPayment')
        ->assertHasErrors([
            'billing_phone', 'card_holder_name', 'card_number', 'card_expiry_date', 'card_cvc', 'billing_phone',
        ])
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
        ->assertSet('currentStep', 2)
        ->call('finish')
        ->assertSessionHas('success')
        ->assertRedirect(BookingResource::getUrl('view', ['record' => $booking->id]));
});
