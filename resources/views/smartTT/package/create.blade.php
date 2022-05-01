@php
/** @var \App\Models\Package $package */
/** @var \App\Models\Tour $tour */
/** @var \App\Models\Flight $flight */
/** @var \App\Models\Settings\PackageSetting $setting */
/** @var \App\Models\Settings\PackagePricingsSetting $pricingSetting */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('Create Package') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('packages.index') }}">{{ __('Packages') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ __('Create Package') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('packages.store') }}" method="POST" id="createForm">
                @include('partials.error-alert')
                @csrf
                <div class="mb-3 row">
                    <div class="col col-md-6">
                        <label for="tour_id" class="form-label">{{ __('Tour') }}</label>
                        <select id="tour_id" name="tour_id" class="form-control">
                            @foreach ($tours as $tour)
                                <option value="{{ $tour->id }}" @selected(old('tour_id') == $tour->id)>
                                    {{ $tour->name }} ({{ $tour->tour_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col col-md-6">
                        <label class="form-label" for="depart_time">{{ __('Depart Time') }}</label>
                        <input type="datetime-local" class="form-control" name="depart_time" id="depart_time"
                            min="{{ date('Y-m-d\TH:i') }}"
                            value="{{ old('depart_time', now()->format('Y-m-d\TH:i')) }}" />
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="flights">{{ __('Flight') }}</label>
                    <select name="flights[]" class="form-control select2" id="flights" multiple>
                        @foreach ($flights as $flight)
                            <option value="{{ $flight->id }}" @selected(old('flights') == $flight->id)>
                                {{ $flight->asSelection }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" id="is_active" name="is_active"
                        @checked(old('is_active', $setting->default_status))>
                    <label class="form-check-label" for="is_active">
                        {{ __('Active this Package ') }}
                    </label>
                </div>

                <div class="border mb-3"></div>
                <h3>{{ __('Pricing') }}</h3>

                <div class="mb-3 row">
                    <div class="col col-md-4">
                        <label for="name[1]" class="form-label">{{ __('Name') }} 1 </label>
                        <input type="text" class="form-control" name="name[1]" id="name[1]"
                            placeholder="{{ __('Enter Pricing Name 1') }}"
                            value="{{ old('name.1', $pricingSetting->default_namings[0]) }}">
                    </div>
                    <div class="col col-md-3">
                        <label for="price[1]" class="form-label">{{ __('Price') }} 1 </label>
                        <input type="number" class="form-control" name="price[1]" id="price[1]" step="0.01"
                            placeholder="{{ 'Enter Price for Pricing  1' }}" value="{{ old('price.1') }}">
                    </div>
                    <div class="col col-md-3">
                        <label for="total_capacity[1]" class="form-label">{{ __('Total Capacity') }} 1 </label>
                        <input type="number" class="form-control" name="total_capacity[1]" id="total_capacity[1]"
                            placeholder="{{ __('Enter Total Capacity of 1') }}"
                            value="{{ old('total_capacity.1', $pricingSetting->default_capacity[0]) }}" step="1">
                    </div>
                    <div class="col col-md-2">
                        <label for="pricing_is_active-1" class="form-label">{{ __('Active this Pricing') }}</label>
                        <input type="checkbox" class="form-check-input d-block" name="pricing_is_active_1"
                            id="pricing_is_active-1" value="1" @checked($pricingSetting->default_status[0])>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col col-md-4">
                        <label for="name[2]" class="form-label">{{ __('Name') }} 2 </label>
                        <input type="text" class="form-control" name="name[2]" id="name[2]"
                            placeholder="{{ __('Enter Pricing Name 2') }}"
                            value="{{ old('name.2', $pricingSetting->default_namings[1]) }}">
                    </div>
                    <div class="col col-md-3">
                        <label for="price[2]" class="form-label">{{ __('Price') }} 2 </label>
                        <input type="number" class="form-control" name="price[2]" id="price[2]" step="0.01"
                            placeholder="{{ 'Enter Price for Pricing  2' }}" value="{{ old('price.2') }}">
                    </div>
                    <div class="col col-md-3">
                        <label for="total_capacity[2]" class="form-label">{{ __('Total Capacity') }} 2 </label>
                        <input type="number" class="form-control" name="total_capacity[2]" id="total_capacity[2]"
                            placeholder="{{ __('Enter Total Capacity of 2') }}"
                            value="{{ old('total_capacity.2', $pricingSetting->default_capacity[1]) }}" step="1">
                    </div>
                    <div class="col col-md-2">
                        <label for="pricing_is_active-2" class="form-label">{{ __('Active this Pricing') }}</label>
                        <input type="checkbox" class="form-check-input d-block" name="pricing_is_active_2"
                            id="pricing_is_active-2" value="1" @checked($pricingSetting->default_status[1])>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col col-md-4">
                        <label for="name[3]" class="form-label">{{ __('Name') }} 3 </label>
                        <input type="text" class="form-control" name="name[3]" id="name[3]"
                            placeholder="{{ __('Enter Pricing Name 3') }}"
                            value="{{ old('name.3', $pricingSetting->default_namings[2]) }}">
                    </div>
                    <div class="col col-md-3">
                        <label for="price[3]" class="form-label">{{ __('Price') }} 3 </label>
                        <input type="number" class="form-control" name="price[3]" id="price[3]" step="0.01"
                            placeholder="{{ 'Enter Price for Pricing  3' }}" value="{{ old('price.3') }}">
                    </div>
                    <div class="col col-md-3">
                        <label for="total_capacity[3]" class="form-label">{{ __('Total Capacity') }} 3 </label>
                        <input type="number" class="form-control" name="total_capacity[3]" id="total_capacity[3]"
                            placeholder="{{ __('Enter Total Capacity of 3') }}"
                            value="{{ old('total_capacity.3', $pricingSetting->default_capacity[2]) }}" step="1">
                    </div>
                    <div class="col col-md-2">
                        <label for="pricing_is_active-3" class="form-label">{{ __('Active this Pricing') }}</label>
                        <input type="checkbox" class="form-check-input d-block" name="pricing_is_active_3"
                            id="pricing_is_active-3" value="1" @checked($pricingSetting->default_status[2])>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <input type="submit" class="btn btn-outline-primary" value="{{ __('Submit') }}" form="createForm">
        </div>
    </div>
@endsection

@push('script')
    <script>
        $("#flights").select2();
    </script>
@endpush
