@php
/** @var \App\Models\Booking $booking */
@endphp

@extends('smartTT.layouts.app')
@section('title')
    {{ __('Booking Management') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Bookings') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <div class="float-end">
                @can('Create Booking')
                    <a href="{{ route('bookings.create') }}" class="btn btn-outline-success">{{ __('Create') }}</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <livewire:booking-table/>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#indexTable').DataTable();
    </script>
@endpush
