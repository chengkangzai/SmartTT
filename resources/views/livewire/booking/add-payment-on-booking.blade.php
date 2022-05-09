<div class="card">
    <div class="card-header">
        <h1>{{ __('Add Payment') }}</h1>
    </div>
    <div class="card-body">
        @include('partials.error-alert')
        <div class="row">
            @if($currentStep == 1)
                @if ($paymentMethod == 'manual')
                    <span class="my-2">{{ __('Choose payment method') }}</span>
                    <div class="btn-group row mx-0">
                        <button
                            class="col-md-6 btn {{ $manualType == 'cash' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="$set('manualType','cash')">
                            {{ __('Cash') }}
                        </button>
                        <button
                            class="col-md-6 btn {{ $manualType == 'card' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="$set('manualType','card')">
                            {{ __('Credit/Debit Card') }}
                        </button>
                    </div>
                @endif

                @if ($paymentMethod == 'stripe')
                    <span class="my-2">{{ __('Enter Credit/Debit Card Information') }}</span>
                    <div class="container">
                        <div id="card-element" class="form-control py-2"></div>
                        <div class="alert alert-danger mt-4 d-none" id="card-error"></div>
                    </div>
                @endif
                @if ($paymentMethod == 'manual')
                    @if ($manualType == 'cash')
                        <div class="container my-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="false" id="receivedCheckBox"
                                       required wire:model="paymentCashReceived"/>
                                <label class="form-check-label" for="receivedCheckBox">
                                    {{ __('The Cash money is received') }}
                                </label>
                            </div>
                        </div>
                    @endif
                    @if ($manualType == 'card')
                        <div class="container my-2">
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <label for="card-holder-name">{{ __('Card Holder Name') }}</label>
                                    <input type="text" class="form-control" id="card-holder-name"
                                           wire:model="cardHolderName"
                                           wire:change.debounce="validateCard('cardHolderName')"
                                           placeholder="John Wick"/>
                                </div>
                                <div class="col-md-3">
                                    <label for="card-element">{{ __('Credit/Debit Card Number') }}</label>
                                    <input type="text" class="form-control" id="card-element" wire:model="cardNumber"
                                           wire:change.debounce="validateCard('cardNumber')"
                                           placeholder="1234 5678 1234 5678"/>
                                </div>
                                <div class="col-md-2">
                                    <label for="card-expiry-month">{{ __('Expiration Date') }}</label>
                                    <input type="text" class="form-control" id="card-expiry-month"
                                           wire:model="cardExpiry" wire:change.debounce="validateCard('cardExpiry')"
                                           placeholder="{{ __('MM/YY') }}"/>
                                </div>
                                <div class="col-md-2">
                                    <label for="card-expiry-month">{{ __('Security Code') }}</label>
                                    <input type="text" class="form-control" id="card-expiry-month"
                                           wire:model="cardCvc" wire:change.debounce="validateCard('cardCvc')"
                                           placeholder="{{ __('123') }}"/>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                <div class="m-2 border"></div>

                <div class="container row">
                    <h3>{{__('Billing Information')}}</h3>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="billing-name">{{ __('Full Name') }}</label>
                        <input type="text" class="form-control" id="billing-name" wire:model="billingName"
                               wire:change.debounce="validateBilling('billingName')" placeholder="John Wick" />
                    </div>
                    <div class="mb-3 col-12 col-md-6">
                        <label for="billing-phone">{{ __('Phone Number') }}</label>
                        <input type="text" class="form-control" id="billing-phone" wire:model="billingPhone"
                               wire:change.debounce="validateBilling('billingPhone')" placeholder="+60123456789" />
                    </div>
                <p class="my-3">{{ __('Payable : ') }} {{ $defaultCurrency }} {{ number_format($paymentAmount, 2) }}</p>
            @endif
            @if($currentStep == 2)
                <div class="container">
                    <div class="row text-center">
                        <div class="col-md-12">
                            <svg class="icon icon-6xl text-success">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-check') }}"></use>
                            </svg>
                        </div>
                        <div class="col-md-12">
                            @if ($paymentMethod == 'stripe')
                                <h3>{{ __('Payment Processing') }}</h3>
                                <span class="d-block">{{ __('You have successfully made a payment') }}</span>
                                <h3> {{ $defaultCurrency }} {{ number_format($paymentAmount, 2) }}</h3>
                                <p>{{ __('The payment has been successfully processed') }}</p>
                                <p>{{ __('You will receive an email confirmation shortly') }}</p>
                                <a href="{{ $payment->getFirstMedia('invoices')->getUrl() }}"
                                   class="btn btn-outline-primary" target="_blank">
                                    {{ __('Download Invoice') }}
                                </a>
                            @endif
                            @if ($paymentMethod == 'manual')
                                <h3>{{ __('Booking Paid') }}</h3>
                                <h3> {{ $defaultCurrency }} {{ number_format($paymentAmount, 2) }}</h3>
                                <a href="{{ $payment->getFirstMedia('invoices')->getUrl() }}"
                                   class="btn btn-outline-primary" target="_blank">
                                    {{ __('Download Invoice') }}
                                </a>
                                <a href="{{ $payment->getFirstMedia('receipts')->getUrl() }}"
                                   class="btn btn-outline-primary" target="_blank">
                                    {{ __('Download Receipt') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="card-footer">
        <div class="float-end">
            @if ($currentStep == 1 && $paymentMethod == 'stripe')
                <button type="button" class="btn btn-primary" id="payment-button"
                        onclick="pay('{{ json_encode($guests) }}')" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span id="payment-button-spinner" class="spinner-border spinner-border-sm d-none" role="status"
                          aria-hidden="true"></span>
                    {{ __('Pay') }} {{ $defaultCurrency }} {{ number_format($paymentAmount, 2) }}
                </button>
            @endif
            @if ($currentStep == 1 && ($manualType == 'cash' || $manualType == 'card'))
                <button type="button" class="btn btn-primary" wire:click="recordManualPayment"
                        wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ __('Receive') }} {{ $defaultCurrency }} {{ number_format($paymentAmount, 2) }}
                </button>
            @endif
            @if($currentStep == 2)
                <button wire:click="finish" class="btn btn-success mx-1" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ __('Finish') }} &rarr;
                </button>
            @endif
        </div>
    </div>
</div>


@push('script')
    @if($paymentMethod == 'stripe')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe('{{ config('services.stripe.key') }}');
            let elements = stripe.elements({
                locale: '{{ app()->getLocale() }}',
            });
            let cardElement = elements.create('card');
            cardElement.mount('#card-element');

            function pay(guest) {
                $('#payment-button').attr('disabled', true);
                $('#payment-button-spinner').removeClass('d-none');
                stripe.confirmCardSetup('{{$client_secret}}', {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: guest[0].name,
                        },
                    }
                })
                    .then(function (result) {
                        if (result.error) {
                            $('#card-error').text(result.error.message).removeClass('d-none');
                            $('#payment-button-spinner').addClass('d-none');
                            $('#payment-button').attr('disabled', false);
                        } else {
                            $('#card-error').text('').addClass('d-none');
                            $('#payment-button-spinner').addClass('d-none');
                            Livewire.emit('cardSetupConfirmed', result.setupIntent.payment_method);
                        }
                    });
            }
        </script>
    @endif
@endpush
