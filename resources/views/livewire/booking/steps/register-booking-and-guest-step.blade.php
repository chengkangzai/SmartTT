<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('Register Guest') }}</h4>
    </div>
    <div class="card-body">
        @include('smartTT.partials.error-alert')
        <div class="alert alert-primary d-flex flex-row align-items-center" role="alert">
            <ul class="my-0 flex-grow-1">
                <li>{{ __('Please note that the price is per person') }}</li>
                <li>{{ __('Child is defined as age between 2 and 12') }}</li>
            </ul>
        </div>
        <div class="container">
            <a wire:click="addNewGuest" class="btn btn-outline-dark my-2">{{ __('Add New guest') }}</a>
            <a wire:click="addNewChild" class="btn btn-outline-dark my-2">{{ __('Add New Child') }}</a>
        </div>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th></th>
                <th>{{ __('Guest Name') }}</th>
                <th>{{ __('Package') }} </th>
                <th>{{ __('Price') }} {{ __('Per Pax') }}</th>
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
                        <input type="text" wire:model.debounce="guests.{{ $i }}.name"
                               id="guest-{{ $i }}-name" class="form-control" aria-label="Name">
                    </td>
                    <td>
                        @if (!$guest['is_child'])
                            <select wire:model.debounce="guests.{{ $i }}.pricing"
                                    id="guest-{{ $i }}-pricings" class="form-control" aria-label="Pricing"
                                    required wire:change="updatePrice({{ $i }})">
                                @foreach ($pricingsHolder as $pricing)
                                    <option value="{{ $pricing['id'] }}"
                                            @if ($loop->first) selected @endif>
                                        {{ $pricing['name'] }}
                                        ({{ $pricing['available_capacity'] . ' ' . __('Seat Left') }})
                                        ({{ $defaultCurrency }} {{ number_format($pricing['price'], 2) }})
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <p class="float-end"> {{ number_format($guest['price'], 2) }}</p>
                        @endif
                    </td>
                    <td>
                        <p>{{ $defaultCurrency }} {{ number_format($guest['price'], 2) }}</p>
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
                    <p class="fw-bold">{{ $defaultCurrency }} {{ number_format($totalPrice, 2) }}</p>
                </td>
            </tfoot>
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
