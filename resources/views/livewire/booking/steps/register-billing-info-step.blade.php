<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('Billing Information') }}</h4>
    </div>
    <div class="card-body">
        @include('smartTT.partials.error-alert')
        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="billing-name">{{ __('Full Name') }}</label>
                <input type="text" class="form-control" id="billing-name" wire:model="billingName"
                       wire:change.debounce="validateBilling('billingName')" placeholder="John Wick"/>
            </div>
            <div class="mb-3 col-12 col-md-6">
                <label for="billing-phone">{{ __('Phone Number') }}</label>
                <input type="text" class="form-control" id="billing-phone" wire:model="billingPhone"
                       wire:change.debounce="validateBilling('billingPhone')" placeholder="60123456789"/>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="float-end">
            <button wire:click="previousStep" class="btn btn-primary mx-1" wire:loading.attr="disabled">
                &larr; {{ __('Previous Step') }}
            </button>
            <button wire:click="nextStep" class="btn btn-primary mx-1" wire:loading.attr="disabled">
                <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                {{ __('Next Step') }} &rarr;
            </button>
        </div>
    </div>
</div>
