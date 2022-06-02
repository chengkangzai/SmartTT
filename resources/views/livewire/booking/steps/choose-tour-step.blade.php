<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('Choose Tour') }}</h4>
    </div>
    <div class="card-body">
        @include('smartTT.partials.error-alert')
        <div class="form-group">
            <label for="tour">{{ __('Tour') }}</label>
            <select wire:model="tour" class="form-control" id="tour">
                <option value="0">{{ __('Please Select') }}</option>
                @foreach ($tours as $tour)
                    <option value="{{ $tour->id }}">{{ $tour->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="card-footer">
        <div class="float-end">
            <button wire:click="nextStep" class="btn btn-primary mx-1" wire:loading.attr="disabled">
                <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                {{ __('Next Step') }} &rarr;
            </button>
        </div>
    </div>
</div>
