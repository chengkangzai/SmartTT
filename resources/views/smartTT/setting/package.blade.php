@php
/** @var \App\Models\Settings\PackageSetting $setting */
@endphp

@extends('layouts.app')

@section('title')
    {{ __('Package Settings') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('Settings') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Package Setting') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Package Settings') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update', 'package') }}" method="post" id="storeForm">
                @include('partials.error-alert')
                @csrf
                <div class="mb-3">
                    <label for="default_status">{{ __('Default Status') }}</label>
                    <select name="default_status" id="default_status" class="form-select">
                        <option value="0" @selected(old('default_status', $setting->default_status) == 0)>
                            {{ __('Inactive') }}
                        </option>
                        <option value="1" @selected(old('default_status', $setting->default_status) == 1)>
                            {{ __('Active') }}
                        </option>
                    </select>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline-primary" form="storeForm">
        </div>
    </div>
@endsection
