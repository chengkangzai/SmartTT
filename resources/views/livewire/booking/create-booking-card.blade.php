@php
/** @var \App\Models\Package $package */
/** @var \App\Models\PackagePricing $pricing */
@endphp

<div class="card">
    <div class="card-header with-border">
        <h3 class="card-title">{{ __('Create Booking') }}</h3>
    </div>
    <div class="card-body">
        @include('partials.error-alert')
        @if ($currentStep == 1)
            <div class="form-group">
                <label for="tour">{{ __('Tour') }}</label>
                <select wire:model="tour" class="form-control" id="tour">
                    <option value="0">{{ __('Please Select') }}</option>
                    @foreach ($tours as $tour)
                        <option value="{{ $tour->id }}">{{ $tour->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        @if ($currentStep == 2)
            <table class="table table-bordered table-striped">
                <tr>
                    <th>{{ __('Depart Time') }}</th>
                    <th>{{ __('Price') }} ({{ $defaultCurrency }})</th>
                    <th>{{ __('Seat') }}</th>
                    <th>{{ __('Select') }}</th>
                </tr>
                @forelse($packages as $package)
                    <tr>
                        <td>{{ $package->depart_time }}</td>
                        <td>{{ $package->price, 2 }}</td>
                        <td>{{ $package->pricings->sum('available_capacity') }}</td>
                        <td>
                            <input type="radio" wire:model="package" value="{{ $package->id }}"
                                aria-label="{{ __('Choose this package') }}">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">{{ __('No Package Available') }}</td>
                    </tr>
                @endforelse
            </table>
        @endif
        @if ($currentStep == 3)
            <h4>{{ __('Register Guest') }}</h4>
            <div class="float-end btn-group">
                <a wire:click="addNewGuest" class="btn btn-outline-primary my-2">{{ __('Add New guest') }}</a>
                <a wire:click="addNewChild" class="btn btn-outline-primary my-2">{{ __('Add New Child') }}</a>
            </div>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('No') }}</th>
                        <th>{{ __('Guest Name') }}</th>
                        <th>{{ __('Package') }} ({{ $defaultCurrency }})</th>
                        <th>{{ __('Price') }} ({{ $defaultCurrency }})</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($guests as $i => $guest)
                        <tr>
                            <td>
                                {{ $i + 1 }}
                            </td>
                            <td>
                                <input type="text" wire:model="guests.{{ $i }}.name"
                                    id="guest-{{ $i }}-name" class="form-control" aria-label="Name">
                            </td>
                            <td>
                                @if (!$guest['is_child'])
                                    <select wire:model="guests.{{ $i }}.pricing"
                                        id="guest-{{ $i }}-pricings" class="form-control"
                                        aria-label="Pricing" required wire:change="updatePrice({{ $i }})">
                                        @foreach ($pricingsHolder as $pricing)
                                            <option value="{{ $pricing['id'] }}"
                                                @if ($loop->first) selected @endif>
                                                {{ $pricing['name'] }}
                                                ({{ $pricing['available_capacity'] . ' ' . __('Left') }})
                                                ({{ number_format($pricing['price'], 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <p class="float-end"> {{ number_format($guest['price'], 2) }}</p>
                                @endif
                            </td>
                            <td>
                                <p> {{ number_format($guest['price'], 2) }}</p>
                            </td>
                            <td>
                                <button wire:click="removeGuest({{ $i }})" class="btn btn-outline-danger"
                                    @if ($loop->first) disabled @endif>
                                    <svg class="icon icon-lg">
                                        <use xlink:href="{{ asset('icons/coreui.svg#cil-trash') }}"></use>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <p class="float-end fw-bold ">{{ __('Total Price') }}</p>
                        </td>
                        <td colspan="2">
                            <p class="fw-bold">{{ number_format($totalPrice, 2) }}</p>
                        </td>
                </tfoot>
            </table>

        @endif
        @if ($currentStep == 4)
            <p>{{ __('Please confirm your booking details') }}</p>
            <table class="table table-bordered">
                <tr>
                    <td>
                        <p class="float-end">{{ __('Tour Name') }}</p>
                    </td>
                    <td>{{ $tours->find($tour)->first()->name }}</td>
                </tr>
                <tr>
                    <td>
                        <p class="float-end">{{ __('Itinerary') }}</p>
                    </td>
                    <td>
                        <a href="{{ $tours->find($tour)->first()->getFirstMedia('itinerary')->getUrl() }}"
                            target="_blank" class="btn btn-outline-primary">
                            {{ __('Itinerary') }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="float-end">{{ __('Depart Date') }}</p>
                    </td>
                    <td>{{ $packages->find($package)->depart_time->toDayDateTimeString() }}</td>
                </tr>
                <tr>
                    <td>
                        <p class="float-end">{{ __('Guests') }}</p>
                    </td>
                    <td>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Package') }}</th>
                                    <th>{{ __('Price') }} ({{ $defaultCurrency }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guests as $guest)
                                    <tr>
                                        <td>{{ $guest['name'] }}</td>
                                        <td>{{ $pricings->find($guest['pricing'])->name }}</td>
                                        <td>{{ number_format($guest['price'], 2) }}</td>
                                    </tr>
                                @endforeach
                        </table>
                    </td>
                </tr>
            </table>
        @endif
        @if ($currentStep == 5)
            <div class="row">
                <span class="my-2">{{ __('Choose payment type') }}</span>
                <div class="btn-group row mx-0 ">
                    <button class="col-md-6 btn {{ $paymentType == 'full' ? 'btn-primary' : 'btn-outline-primary' }}"
                        wire:click="$set('paymentType','full')">
                        {{ __('Full Payment') }}
                    </button>
                    <button
                        class="col-md-6 btn {{ $paymentType == 'deposit' ? 'btn-primary' : 'btn-outline-primary' }}"
                        wire:click="$set('paymentType','deposit')">
                        {{ __('Reservation Deposit') }}
                    </button>
                </div>
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
                                    required wire:model="paymentCashReceived" />
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
                                        wire:change.debounce="validateCard('cardHolderName')" placeholder="John Wick" />
                                </div>
                                <div class="col-md-3">
                                    <label for="card-element">{{ __('Credit/Debit Card Number') }}</label>
                                    <input type="text" class="form-control" id="card-element" wire:model="cardNumber"
                                        wire:change.debounce="validateCard('cardNumber')"
                                        placeholder="1234 5678 1234 5678" />
                                </div>
                                <div class="col-md-2">
                                    <label for="card-expiry-month">{{ __('Expiration Date') }}</label>
                                    <input type="text" class="form-control" id="card-expiry-month"
                                        wire:model="cardExpiry" wire:change.debounce="validateCard('cardExpiry')"
                                        placeholder="{{ __('MM/YY') }}" />
                                </div>
                                <div class="col-md-2">
                                    <label for="card-expiry-month">{{ __('Security Code') }}</label>
                                    <input type="text" class="form-control" id="card-expiry-month"
                                        wire:model="cardCvc" wire:change.debounce="validateCard('cardCvc')"
                                        placeholder="{{ __('123') }}" />
                                </div>
                            </div>
                        </div>
                    @endif
                    <p class="my-3">{{ __('Payable : ') }} {{ $defaultCurrency }}
                        {{ number_format($paymentAmount, 2) }}</p>
                @endif
            </div>
        @endif
        @if ($currentStep == 6)
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-12">
                        <svg class="icon icon-6xl text-success">
                            <use xlink:href="{{ asset('icons/coreui.svg#cil-check') }}"></use>
                        </svg>
                    </div>
                    <div class="col-md-12">
                        <h3>{{ __('Invoice Paid') }}</h3>
                        <span class="d-block">{{ __('You have successfully made a payment') }}</span>
                        <h3> {{ $defaultCurrency }} {{ $paymentAmount }}</h3>
                        <p>{{ __('The payment has been successfully processed') }}</p>
                        <p>{{ __('You will receive an email confirmation shortly') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="card-footer">
        <div class="float-end">
            @if ($currentStep > 1 && $currentStep < 5)
                <button wire:click="previousStep" class="btn btn-primary mx-1"> &larr; {{ __('Previous') }}</button>
            @endif
            @if ($currentStep < 4)
                <button wire:click="nextStep" class="btn btn-primary mx-1">{{ __('Next') }} &rarr;</button>
            @endif
            @if ($currentStep == 4)
                <button wire:click="nextStep" class="btn btn-success mx-1">
                    {{ __('Confirm & Proceed to Payment') }} &rarr;
                </button>
            @endif
            @if ($currentStep == 5 && $paymentMethod == 'stripe')
                <button type="button" class="btn btn-primary" id="payment-button"
                    onclick="pay('{{ json_encode($guests) }}')">
                    {{ __('Pay') }} {{ $defaultCurrency }} {{ number_format($paymentAmount, 2) }}
                </button>
            @endif
            @if ($currentStep == 5 && $paymentMethod == 'manual')
                <button type="button" class="btn btn-primary" wire:click="recordManualPayment">
                    {{ __('Pay') }} {{ $defaultCurrency }} {{ number_format($paymentAmount, 2) }}
                </button>
            @endif
            @if ($currentStep == 5 && $paymentMethod == 'cash')
                <button wire:click="nextStep" class="btn btn-primary mx-1">{{ __('Next') }} &rarr;</button>
            @endif
            @if ($currentStep == 6)
                <button wire:click="nextStep" class="btn btn-success mx-1">{{ __('Finish') }} &rarr;</button>
            @endif
        </div>
    </div>
</div>

@push('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        let clientSecret;
        let elements;
        let cardElement;
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        window.addEventListener('getReadyForPayment', (event) => {
            clientSecret = event.detail.clientSecret;

            //create an instance of the card Element that look like bootstrap form
            elements = stripe.elements({
                locale: '{{ app()->getLocale() }}',
            });
            cardElement = elements.create('card');
            cardElement.mount('#card-element');
        });

        function pay(guest) {
            stripe.confirmCardSetup(clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: guest[0].name,
                        },
                    }
                })
                .then(function(result) {
                    if (result.error) {
                        $('#card-error').text(result.error.message).removeClass('d-none');
                        $('#payment-button').attr('disabled', false);
                    } else {
                        $('#card-error').text('').addClass('d-none');
                        Livewire.emit('cardSetupConfirmed', result.setupIntent.payment_method);
                    }
                });
        }
    </script>
@endpush
