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
                                <button wire:click="removeGuest({{ $i }})" class="btn btn-outline-danger">
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
                    <td>{{ $tourModel->name }}</td>
                </tr>
                <tr>
                    <td>
                        <p class="float-end">{{ __('Itinerary') }}</p>
                    </td>
                    <td>
                        <a href="{{ $tourModel->getFirstMedia('itinerary')->getUrl() }}" target="_blank"
                            class="btn btn-outline-primary">
                            {{ __('Itinerary') }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="float-end">{{ __('Depart Date') }}</p>
                    </td>
                    <td>{{ $packageModel->depart_time->toDayDateTimeString() }}</td>
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
    </div>
    <div class="card-footer">
        <div class="float-end">
            @if ($currentStep > 1)
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
        </div>
    </div>
</div>
