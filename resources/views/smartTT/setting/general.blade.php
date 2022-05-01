@php
/** @var \App\Models\Settings\GeneralSetting $setting */
@endphp

@extends('layouts.app')

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
                @include('partials.error-alert')
                @csrf
                <div class="mb-3">
                    <label for="name">{{ __('Site Name') }}</label>
                    <input type="text" name="site_name" id="name" class="form-control" value="{{ $setting->site_name }}">
                </div>
                <div class="mb-3">
                    <label for="default_language">{{ __('Default Language') }}</label>
                    <select name="default_language" id="default_language" class="form-select">
                        @foreach ($setting->supported_language as $language)
                            <option value="{{ $language }}" @selected(old('default_language', $setting->default_language) == $language)>
                                {{ $language }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- @dd($setting->default_timezone) --}}
                <div class="mb-3">
                    <label for="default_timezone">{{ __('Default Timezone') }}</label>
                    <select name="default_timezone" id="default_timezone" class="form-select">
                        @foreach ($viewBag['timezones'] as $timezone)
                            <option value="{{ $timezone }}" @selected(old('default_timezone', $setting->default_timezone->getName()) == $timezone)>
                                {{ $timezone }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="row mb-md-2">
                    <div class="col-12 mb-2 mb-md-0 col-md-6">
                        <label for="default_currency">{{ __('Default Currency') }}</label>
                        <input type="text" class="form-control" name="default_currency" id="default_currency"
                            value="{{ old('default_currency', $setting->default_currency) }}">
                    </div>
                    <div class="col-12 mb-2 mb-md-0 col-md-6">
                        <label for="default_currency_symbol">{{ __('Default Currency Symbol') }}</label>
                        <input type="text" class="form-control" name="default_currency_symbol"
                            id="default_currency_symbol"
                            value="{{ old('default_currency_symbol', $setting->default_currency_symbol) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="default_country">{{ __('Default Country') }}</label>
                    <select name="default_country" id="default_country" class="form-select">
                        @foreach ($viewBag['countries'] as $country)
                            <option value="{{ $country->name }}" @selected(old('default_country', $setting->default_country) == $country->name)>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline-primary" form="storeForm">
        </div>
    </div>
@endsection
