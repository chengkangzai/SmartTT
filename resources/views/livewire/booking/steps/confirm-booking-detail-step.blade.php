<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('Confirm Booking Detail') }}</h4>
    </div>
    <div class="card-body">
        <p>{{ __('Please confirm your booking details') }}</p>
        <table class="table table-bordered">
            <tr>
                <td>
                    <p class="float-end">{{ __('Tour Name') }}</p>
                </td>
                <td>{{ $tour->name ?? '' }}</td>
            </tr>
            <tr>
                <td>
                    <p class="float-end">{{ __('Itinerary') }}</p>
                </td>
                <td>
                    <a href="{{ $tour?->getFirstMediaUrl('itinerary') ?? '#' }}" target="_blank"
                        class="btn btn-outline-primary">
                        {{ __('Itinerary') }}
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="float-end">{{ __('Depart Date') }}</p>
                </td>
                <td>{{ $package?->depart_time?->translatedFormat(config('app.date_format')) ?? '' }}</td>
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
                                <th>{{ __('Price') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($guests as $guest)
                                <tr>
                                    <td>{{ $guest['name'] ?? '' }}</td>
                                    <td>{{ $pricings->find($guest['pricing'])?->name ?? 'N/A' }}</td>
                                    <td>{{ money($guest['price'], $defaultCurrency) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <span class="float-end fw-bold ">{{ __('Total Price') }}</span>
                                </td>
                                <td>{{ money($totalPrice,$defaultCurrency) }}</td>
                        </tfoot>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="float-end">{{ __('Billing Information') }}</p>
                </td>
                <td>
                    <table class="table table-bordered">
                        <tr>
                            <td>{{ __('Full Name') }}</td>
                            <td>{{ $billingName }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Phone Number') }}</td>
                            <td>{{ $billingPhone }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="alert alert-danger">
            {{ __('Please note that you can not change your booking after this step') }} <br>
            {{ __('If you want to change your booking after booking, please contact us') }}
        </div>
    </div>
    <div class="card-footer">
        <div class="float-end">
            <button wire:click="previousStep" class="btn btn-primary mx-1" wire:loading.attr="disabled">
                &larr; {{ __('Previous Step') }}
            </button>
            <button wire:click="nextStep" class="btn btn-success mx-1" wire:loading.attr="disabled">
                <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                {{ __('Confirm & Proceed to Payment') }} &rarr;
            </button>
        </div>
    </div>
</div>
