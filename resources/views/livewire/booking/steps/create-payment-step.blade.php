<div class="card" wire:init="dispatchBrowserEventIfInStripeMode">
    <div class="card-header">
        <h4 class="card-title">
            {{ __('Payment') }}
        </h4>
    </div>
    <div class="card-body">
        @include('smartTT.partials.error-alert')
        <div class="row">
            <span class="my-2">{{ __('Choose payment type') }}</span>
            <div class="btn-group row mx-0 ">
                <button class="col-md-6 btn {{ $paymentType == 'full' ? 'btn-primary' : 'btn-outline-primary' }}"
                        wire:click="$set('paymentType','full')">
                    {{ __('Full Payment') }}
                </button>
                <button
                        class="col-md-6 btn {{ $paymentType == 'reservation' ? 'btn-primary' : 'btn-outline-primary' }}"
                        wire:click="$set('paymentType','reservation')">
                    {{ __('Reservation Deposit') }}
                </button>
            </div>
            @if ($paymentMethod == 'manual')
                <span class="my-2">{{ __('Choose payment method') }}</span>
                <div class="btn-group row mx-0">
                    <button class="col-md-6 btn {{ $manualType == 'cash' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="$set('manualType','cash')">
                        {{ __('Cash') }}
                    </button>
                    <button class="col-md-6 btn {{ $manualType == 'card' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="$set('manualType','card')">
                        {{ __('Credit/Debit Card') }}
                    </button>
                </div>
            @endif

            @if ($paymentMethod == 'stripe')
                <span class="my-2">{{ __('Credit/Debit Card Information') }}</span>
                <div class="container">
                    <div id="card-element" class="form-control py-2"></div>
                    <div class="alert alert-danger mt-4 d-none" id="card-error"></div>
                </div>
            @endif

            @if ($paymentMethod == 'manual')
                @if ($manualType == 'cash')
                    <div class="container my-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="false" id="receivedCheckBox" required
                                   wire:model="paymentCashReceived"/>
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
                                       wire:model="cardHolderName" wire:change.debounce="validateCard('cardHolderName')"
                                       placeholder="John Wick"/>
                            </div>
                            <div class="col-md-3">
                                <label for="card-element">{{ __('Credit/Debit Card Number') }}</label>
                                <input type="text" class="form-control" id="card-element" wire:model="cardNumber"
                                       wire:change.debounce="validateCard('cardNumber')"
                                       placeholder="1234567812345678"/>
                            </div>
                            <div class="col-md-2">
                                <label for="card-expiry-month">{{ __('Expiration Date') }}</label>
                                <input type="text" class="form-control" id="card-expiry-month" wire:model="cardExpiry"
                                       wire:change.debounce="validateCard('cardExpiry')"
                                       placeholder="{{ __('MM/YY') }}"/>
                            </div>
                            <div class="col-md-2">
                                <label for="card-expiry-month">{{ __('Security Code') }}</label>
                                <input type="text" class="form-control" id="card-expiry-month" wire:model="cardCvc"
                                       wire:change.debounce="validateCard('cardCvc')" placeholder="123"/>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <p class="my-3">
                {{ __('Payable : ') }} {{ $defaultCurrency }} {{ number_format($paymentAmount, 2) }}
            </p>
            <p>
                @if ($paymentType == 'reservation')
                    {{ __('Full Payment must be paid before : ') }}
                    {{ $booking->package->depart_time->addDays(config('invoices.date.pay_until_days'))->format('d M Y') }}
                @endif
            </p>
        </div>
    </div>
    <div class="card-footer">
        <div class="float-end">
            @if ($paymentMethod == 'stripe')
                <button type="button" class="btn btn-primary" id="payment-button" onclick="pay()"
                        wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span id="payment-button-spinner" class="spinner-border spinner-border-sm d-none" role="status"
                          aria-hidden="true"></span>
                    {{ __('Pay') }} {{ $defaultCurrency }} {{ number_format($paymentAmount, 2) }}
                </button>
            @endif
            @if ($paymentMethod == 'manual')
                <button type="button" class="btn btn-primary" wire:click="nextStep" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ __('Receive') }} {{ $defaultCurrency }} {{ number_format($paymentAmount, 2) }}
                </button>
            @endif
        </div>
    </div>
</div>
