@php
/** @var \App\Models\Booking $booking */
/** @var \App\Models\Package $package */
@endphp

@extends('layouts.app')
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

    <livewire:booking.create-booking-card />
@endsection
