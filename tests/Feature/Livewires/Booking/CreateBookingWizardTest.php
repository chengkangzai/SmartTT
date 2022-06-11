<?php

use App\Http\Livewire\Booking\CreateBookingWizard;
use App\Http\Livewire\Booking\Steps\ChoosePackageStep;
use App\Http\Livewire\Booking\Steps\ChooseTourStep;
use App\Http\Livewire\Booking\Steps\ConfirmBookingDetailStep;
use App\Http\Livewire\Booking\Steps\CreatePaymentStep;
use App\Http\Livewire\Booking\Steps\RegisterBillingInfoStep;
use App\Http\Livewire\Booking\Steps\RegisterBookingAndGuestStep;
use App\Models\Package;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed([
       DatabaseSeeder::class,
    ]);
});

it('should be mountable', function () {
    Livewire::test(CreateBookingWizard::class, ['packageId' => null])
        ->assertSuccessful()
        ->assertSee('Choose Tour');
});


it('should be able to book a packages as customer', function () {
    $this->actingAs(User::factory()->customer()->create());
    $package = Package::first();

    $wizard = Livewire::test(CreateBookingWizard::class, [
        'packageId' => null,
    ])
        ->assertSuccessful()
        ->assertSee('Choose Tour');

    Livewire::test(ChooseTourStep::class)
        ->set('tour', 0)
        ->call('nextStep')
        ->assertHasErrors()
        ->set('tour', $package->tour_id)
        ->call('nextStep')
        ->assertHasNoErrors()
        ->emitEvents()->in($wizard);

    $wizard->assertSee('Choose Package');

    Livewire::test(ChoosePackageStep::class)
        ->set('package', 0)
        ->call('nextStep')
        ->assertHasErrors()
        ->set('package', $package->id)
        ->call('nextStep')
        ->assertHasNoErrors()
        ->emitEvents()->in($wizard);

    $wizard->assertSee('Register Guest');
//
//    Livewire::test(RegisterBookingAndGuestStep::class)
//        ->set('package', $package->id)
//        ->call('updatePricings')
//        ->set('guests.0.name', '')
//        ->call('nextStep')
//        ->assertHasErrors()
//        ->set('guests.0.pricing', 0)
//        ->call('nextStep')
//        ->assertHasErrors()
//        ->set('guests.0.price', 0)
//        ->call('nextStep')
//        ->assertHasErrors()
//        ->set('guests.0.name', 'John Doe')
//        ->set('guests.0.pricing', $package->pricings->first()->id)
//        ->set('guests.0.price', $package->pricings->first()->price)
//        ->set('guests.0.is_child', false)
//        ->call('addNewGuest')
//        ->assertCount('guests', 2)
//        ->call('removeGuest', 1)
//        ->assertCount('guests', 1)
//        ->call('addNewChild')
//        ->assertCount('guests', 2)
//        ->call('removeGuest', 1)
//        ->assertCount('guests', 1)
//        ->call('nextStep')
//        ->assertHasNoErrors()
//        ->emitEvents()->in($wizard);
//
//    $wizard->assertSee('Billing Information');
//
//    Livewire::test(RegisterBillingInfoStep::class)
//        ->set('billingName', '')
//        ->call('nextStep')
//        ->call('validateBilling', 'billingName')
//        ->assertHasErrors()
//        ->set('billingPhone', '')
//        ->call('nextStep')
//        ->call('validateBilling', 'billingPhone')
//        ->assertHasErrors()
//        ->set('billingName', 'Another John Doe')
//        ->set('billingPhone', '0123456789')
//        ->call('nextStep')
//        ->assertHasNoErrors()
//        ->emitEvents()->in($wizard);
//
//    $wizard->assertSee('Confirm Booking Detail')
//        ->assertSee($package->tour->name)
//        ->assertSee($package->depart_time->translatedFormat(config('app.date_format')))
//        ->assertSee('Another John Doe')
//        ->assertSee('0123456789');
//
//    Livewire::test(ConfirmBookingDetailStep::class)
//        ->call('nextStep')
//        ->assertHasNoErrors()
//        ->emitEvents()->in($wizard);
//
//    $wizard->assertSee('Payment');
});

it('should be able to book a packages as staff', function () {
    $this->actingAs(User::factory()->staff()->create());
    $package = Package::first();

    $wizard = Livewire::test(CreateBookingWizard::class, [
        'packageId' => null,
    ])
        ->assertSuccessful()
        ->assertSee('Choose Tour');

    Livewire::test(ChooseTourStep::class)
        ->set('tour', 0)
        ->call('nextStep')
        ->assertHasErrors()
        ->set('tour', $package->tour_id)
        ->call('nextStep')
        ->assertHasNoErrors()
        ->emitEvents()->in($wizard);

    $wizard->assertSee('Choose Package');

    Livewire::test(ChoosePackageStep::class)
        ->set('package', 0)
        ->call('nextStep')
        ->assertHasErrors()
        ->set('package', $package->id)
        ->call('nextStep')
        ->assertHasNoErrors()
        ->emitEvents()->in($wizard);

    $wizard->assertSee('Register Guest');

//    Livewire::test(RegisterBookingAndGuestStep::class)
//        ->set('package', $package->id)
//        ->set('guests.0.name', '')
//        ->call('nextStep')
//        ->assertHasErrors()
//        ->set('guests.0.pricing', 0)
//        ->call('nextStep')
//        ->assertHasErrors()
//        ->set('guests.0.price', 0)
//        ->call('nextStep')
//        ->assertHasErrors()
//        ->set('guests.0.name', 'John Doe')
//        ->set('guests.0.pricing', $package->pricings->first()->id)
//        ->set('guests.0.price', $package->pricings->first()->price)
//        ->set('guests.0.is_child', false)
//        ->call('addNewGuest')
//        ->assertCount('guests', 2)
//        ->call('removeGuest', 1)
//        ->assertCount('guests', 1)
//        ->call('addNewChild')
//        ->assertCount('guests', 2)
//        ->call('removeGuest', 1)
//        ->assertCount('guests', 1)
//        ->call('nextStep')
//        ->assertHasNoErrors()
//        ->emitEvents()->in($wizard);
//
//    $wizard->assertSee('Billing Information');
//
    Livewire::test(RegisterBillingInfoStep::class)
        ->set('billingName', '')
        ->call('nextStep')
        ->call('validateBilling', 'billingName')
        ->assertHasErrors()
        ->set('billingPhone', '')
        ->call('nextStep')
        ->call('validateBilling', 'billingPhone')
        ->assertHasErrors()
        ->set('billingName', 'Another John Doe')
        ->set('billingPhone', '0123456789')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->emitEvents()->in($wizard);

//    $wizard->assertSee('Confirm Booking Detail')
//        ->assertSee($package->tour->name)
//        ->assertSee($package->depart_time->translatedFormat(config('app.date_format')))
//        ->assertSee('Another John Doe')
//        ->assertSee('0123456789');
//
//    Livewire::test(ConfirmBookingDetailStep::class)
//        ->call('nextStep')
//        ->assertHasNoErrors()
//        ->emitEvents()->in($wizard);
//
//    $wizard->assertSee('Payment');
//
//    Livewire::test(CreatePaymentStep::class)
//        ->call('nextStep')
//        ->set('guests.0.name', 'John Doe')
//        ->set('guests.0.pricing', $package->pricings->first()->id)
//        ->set('guests.0.price', $package->pricings->first()->price)
//        ->set('guests.0.is_child', false)
//        ->set('billingName', 'Another John Doe')
//        ->set('billingPhone', '0123456789')
//        ->set('cardHolderName', 'John Doe')
//        ->set('paymentAmount', $package->pricings->first()->price)
//        ->call('validateCard', 'cardHolderName')
//        ->assertHasNoErrors('cardHolderName')
//        ->set('cardNumber', '4111111111111111')
//        ->call('validateCard', 'cardNumber')
//        ->assertHasNoErrors('cardNumber')
//        ->set('cardExpiry', '01/24')
//        ->call('validateCard', 'cardExpiry')
//        ->assertHasNoErrors('cardExpiry')
//        ->set('cardCvc', '123')
//        ->call('validateCard', 'cardCvc')
//        ->assertHasNoErrors('cardCvc')
//        ->set('manualType', 'cash')
//        ->assertHasNoErrors(['cardHolderName', 'cardNumber', 'cardExpiry', 'cardCvc'])
//        ->call('nextStep')
//        ->assertHasNoErrors()
//        ->set('paymentCashReceived', true)
//        ->call('nextStep')
//        ->assertHasNoErrors()
//        ->emitEvents()->in($wizard);

//    $wizard->assertSee('Booking Paid')
//        ->assertSee('Download Invoice')
//        ->assertSee('Download Receipt');
});
