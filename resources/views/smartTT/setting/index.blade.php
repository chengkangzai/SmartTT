@extends('layouts.app')
@section('title')
    {{ __('Settings Management') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Settings') }}</li>
        </ol>
    </nav>

    <div class="accordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingGeneral">
                <button class="accordion-button collapsed" type="button" data-coreui-toggle="collapse"
                    data-coreui-target="#collapseGeneral" aria-controls="collapseGeneral">
                    {{ __('General') }}
                </button>
            </h2>
            <div id="collapseGeneral" class="accordion-collapse collapse" aria-labelledby="headingGeneral">
                <div class="accordion-body">
                    <x-setting-table mode="general" :settings="$settings" />
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTour">
                <button class="accordion-button collapsed" type="button" data-coreui-toggle="collapse"
                    data-coreui-target="#collapseTour" aria-expanded="false" aria-controls="collapseTour">
                    {{ __('Tour') }}
                </button>
            </h2>
            <div id="collapseTour" class="accordion-collapse collapse" aria-labelledby="headingTour">
                <div class="accordion-body">
                    <x-setting-table mode="tour" :settings="$settings" />
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingPackage">
                <button class="accordion-button collapsed" type="button" data-coreui-toggle="collapse"
                    data-coreui-target="#collapsePackage" aria-expanded="false" aria-controls="collapsePackage">
                    {{ __('Package') }}
                </button>
            </h2>
            <div id="collapsePackage" class="accordion-collapse collapse" aria-labelledby="headingPackage">
                <div class="accordion-body">
                    <x-setting-table mode="package" :settings="$settings" />
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingPackagePricing">
                <button class="accordion-button collapsed" type="button" data-coreui-toggle="collapse"
                    data-coreui-target="#collapsePackagePricing" aria-expanded="false"
                    aria-controls="collapsePackagePricing">
                    {{ __('Package Pricing') }}
                </button>
            </h2>
            <div id="collapsePackagePricing" class="accordion-collapse collapse" aria-labelledby="headingPackagePricing">
                <div class="accordion-body">
                    <x-setting-table mode="package_pricing" :settings="$settings" />
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFlight">
                <button class="accordion-button collapsed" type="button" data-coreui-toggle="collapse"
                    data-coreui-target="#collapseFlight" aria-expanded="false" aria-controls="collapseFlight">
                    {{ __('Flight') }}
                </button>
            </h2>
            <div id="collapseFlight" class="accordion-collapse collapse" aria-labelledby="headingFlight">
                <div class="accordion-body">
                    <x-setting-table mode="flight" :settings="$settings" />
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingBooking">
                <button class="accordion-button collapsed" type="button" data-coreui-toggle="collapse"
                    data-coreui-target="#collapseBooking" aria-expanded="false" aria-controls="collapseBooking">
                    {{ __('Booking') }}
                </button>
            </h2>
            <div id="collapseBooking" class="accordion-collapse collapse" aria-labelledby="headingBooking">
                <div class="accordion-body">
                    <x-setting-table mode="booking" :settings="$settings" />
                </div>
            </div>
        </div>
    </div>
@endsection
