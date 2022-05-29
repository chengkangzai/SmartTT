@php
/** @var \App\Models\PackagePricing $packagePricing */
@endphp

@extends('smartTT.layouts.app')
@section('title')
    {{ __('Create Package Pricing') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('packages.show', $packagePricing) }}">{{ __('Packages') }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
        </ol>
    </nav>


    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ __('Update') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('packagePricings.update', $packagePricing) }}" id="editForm" method="POST">
                @include('partials.error-alert')
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="{{ __('Pricing Name') }}"
                        value="{{ old('name', $packagePricing->name) }}">
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">{{ __('Price') }}</label>
                    <input type="number" class="form-control" name="price" id="price"
                        placeholder="{{ 'Price for Pricing ' }}" value="{{ old('price', $packagePricing->price) }}"
                        step="0.01">
                </div>
                <div class="mb-3">
                    <label for="total_capacity" class="form-label">{{ __('Total Capacity') }}</label>
                    <input type="number" class="form-control" name="total_capacity" id="total_capacity"
                        placeholder="{{ __('Total Capacity of') }}"
                        value="{{ old('total_capacity', $packagePricing->total_capacity) }}" step="1">
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button form="editForm" type="submit" class="btn btn-outline-primary">{{ __('Submit') }}</button>
        </div>
    </div>
@endsection
