@php
/** @var \App\Models\Settings\BookingSetting $setting */
@endphp

@extends('smartTT.layouts.app')

@section('title')
    {{ __('Booking Settings') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('Settings') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Booking Setting') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Booking Settings') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update', 'booking') }}" method="post" id="storeForm">
                @include('smartTT.partials.error-alert')
                @csrf
                <div class="mb-3">
                    <label for="default_payment_method"> {{ trans('setting.booking.default_payment_method') }}</label>
                    <select name="default_payment_method" id="default_payment_method" class="form-select">
                        @foreach ($setting->supported_payment_method as $method)
                            <option value="{{ $method }}" @selected(old('default_payment_method', $setting->default_payment_method) == $method)>
                                {{ $method }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="charge_per_child">{{ trans('setting.booking.charge_per_child') }}</label>
                    <input name="charge_per_child" id="charge_per_child" class="form-control" type="number"
                        value="{{ old('charge_per_child', $setting->charge_per_child) }}">
                </div>
                <div class="mb-3">
                    <label
                        for="reservation_charge_per_pax">{{ trans('setting.booking.reservation_charge_per_pax') }}</label>
                    <input name="reservation_charge_per_pax" id="reservation_charge_per_pax" class="form-control"
                        type="number"
                        value="{{ old('reservation_charge_per_pax', $setting->reservation_charge_per_pax) }}">
                </div>
            </form>
        </div>
        <div class="card-footer">
            <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline-primary" form="storeForm">
        </div>
    </div>
@endsection
