<div class="card">
    <div class="card-body">
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
                        <a href="{{ $payment->getFirstMediaUrl('invoices') ?? '#' }}" class="btn btn-outline-primary"
                            target="_blank">
                            {{ __('Download Invoice') }}
                        </a>
                    @endif
                    @if ($paymentMethod == 'manual')
                        <h3>{{ __('Booking Paid') }}</h3>
                        <h3> {{ $defaultCurrency }} {{ number_format($paymentAmount, 2) }}</h3>
                        <a href="{{ $payment->getFirstMediaUrl('invoices') ?? '#' }}" class="btn btn-outline-primary"
                            target="_blank">
                            {{ __('Download Invoice') }}
                        </a>
                        <a href="{{ $payment->getFirstMediaUrl('receipts') ?? '#' }}" class="btn btn-outline-primary"
                            target="_blank">
                            {{ __('Download Receipt') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="float-end">
            <button wire:click="nextStep" class="btn btn-success mx-1" wire:loading.attr="disabled">
                <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                {{ __('Finish') }} &rarr;
            </button>
        </div>
    </div>
</div>
