@php
/** @var \App\Models\Settings\GeneralSetting $setting */
@endphp

@extends('smartTT.layouts.app')

@section('title')
    {{ __('General Settings') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('Settings') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('General Setting') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('General Settings') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update', 'general') }}" method="post" id="storeForm">
                @include('smartTT.partials.error-alert')
                @csrf
                <div class="mb-3">
                    <label for="site_name">{{ trans('setting.general.site_name') }}</label>
                    <input type="text" name="site_name" id="site_name" class="form-control"
                        value="{{ $setting->site_name }}">
                </div>
                <div class="mb-3">
                    <label for="default_language">{{ trans('setting.general.default_language') }}</label>
                    <select name="default_language" id="default_language" class="form-select">
                        @foreach ($setting->supported_language as $language)
                            <option value="{{ $language }}" @selected(old('default_language', $setting->default_language) == $language)>
                                {{ $language }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="default_timezone">{{ trans('setting.general.default_timezone') }}</label>
                    <select name="default_timezone" id="default_timezone" class="form-select" multiple>
                        @foreach ($viewBag['timezones'] as $timezone)
                            <option value="{{ $timezone }}" @selected(old('default_timezone', $setting->default_timezone->getName()) == $timezone)>
                                {{ $timezone }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="row mb-md-2">
                    <div class="col-12 mb-2 mb-md-0 col-md-6">
                        <label for="default_currency">{{ trans('setting.general.default_currency') }}</label>
                        <input type="text" class="form-control" name="default_currency" id="default_currency"
                            value="{{ old('default_currency', $setting->default_currency) }}">
                    </div>
                    <div class="col-12 mb-2 mb-md-0 col-md-6">
                        <label
                            for="default_currency_symbol">{{ trans('setting.general.default_currency_symbol') }}</label>
                        <input type="text" class="form-control" name="default_currency_symbol"
                            id="default_currency_symbol"
                            value="{{ old('default_currency_symbol', $setting->default_currency_symbol) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="default_country">{{ trans('setting.general.default_country') }}</label>
                    <select name="default_country" id="default_country" class="form-select" multiple>
                        @foreach ($viewBag['countries'] as $country)
                            <option value="{{ $country->name }}" @selected(old('default_country', $setting->default_country) == $country->name)>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="border my-2"></div>
                <div class="row mb-md-2">
                    <div class="col-12 mb-2 mb-md-0 col-md-6">
                        <label for="company_name">{{ trans('setting.general.company_name') }}</label>
                        <input type="text" class="form-control" name="company_name" id="company_name"
                            value="{{ old('company_name', $setting->company_name) }}">
                    </div>
                    <div class="col-12 mb-2 mb-md-0 col-md-6">
                        <label for="company_phone">{{ trans('setting.general.company_phone') }}</label>
                        <input type="text" class="form-control" name="company_phone" id="company_phone"
                            value="{{ old('company_phone', $setting->company_phone) }}">
                    </div>
                </div>
                <div class="row mb-md-2">
                    <div class="col-12 mb-2 mb-md-0 col-md-6">
                        <label
                            for="business_registration_no">{{ trans('setting.general.business_registration_no') }}</label>
                        <input type="text" class="form-control" name="business_registration_no"
                            id="business_registration_no"
                            value="{{ old('business_registration_no', $setting->business_registration_no) }}">
                    </div>
                    <div class="col-12 mb-2 mb-md-0 col-md-6">
                        <label for="company_email">{{ trans('setting.general.company_email') }}</label>
                        <input type="text" class="form-control" name="company_email" id="company_email"
                            value="{{ old('company_email', $setting->company_email) }}">
                    </div>
                </div>
                <div>
                    <label for="company_address">{{ __('Company Address') }}</label>
                    <textarea name="company_address" id="company_address" class="form-control"
                        rows="3">{{ old('company_address', $setting->company_address) }}</textarea>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline-primary" form="storeForm">
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#default_timezone').select2();
            $('#default_country').select2();
        });
    </script>
@endpush
