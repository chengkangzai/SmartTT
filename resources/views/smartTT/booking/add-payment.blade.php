@extends('layouts.app')
@section('title')
    {{ __('Add Payment') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">{{ __('Bookings') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.show',$booking) }}">{{$booking->id}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Payment') }}</li>
        </ol>
    </nav>

    <livewire:booking.add-payment-on-booking :booking="$booking" />
@endsection



