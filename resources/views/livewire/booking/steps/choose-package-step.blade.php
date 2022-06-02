<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('Choose Package') }}</h4>
    </div>
    <div class="card-body">
        @include('smartTT.partials.error-alert')
        <table class="table table-bordered table-striped">
            <tr>
                <th>{{ __('Depart Time') }}</th>
                <th>{{ __('Price') }} ({{ $defaultCurrency }})</th>
                <th>{{ __('Seat') }}</th>
                <th>{{ __('Select') }}</th>
            </tr>
            @forelse($packages as $package)
                <tr>
                    <td>{{ $package->depart_time->translatedFormat(config('app.date_format')) }}</td>
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
