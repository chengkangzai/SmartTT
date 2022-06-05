@extends('smartTT.layouts.app')
@section('title')
    {{ __('Create Booking') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">{{ __('Bookings') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>
    @if (request('package'))
        <livewire:booking.create-booking-wizard show-step="register-booking-and-guest-step"
            :package-id="request('package')" />
    @else
        <livewire:booking.create-booking-wizard :package-id="request('package')" />
    @endif
@endsection

@include('smartTT.partials.initialStripeScript')
