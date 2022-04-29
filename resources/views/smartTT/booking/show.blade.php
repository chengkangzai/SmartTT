@php
/** @var \App\Models\Booking $booking */
@endphp
@extends('layouts.app')
@section('title')
    {{ __('Booking Management') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">{{ __('Bookings') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Show') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Booking Information') }}</h3>
            <div class="float-end">
                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-outline-primary">{{ __('Edit') }}</a>
                <form action="{{ route('bookings.destroy', $booking) }}" method="POST" style="display: inline">
                    @method('DELETE')
                    @csrf
                    <input class="btn btn-outline-danger" type="submit" value="{{ __('Delete') }}" />
                </form>
                <a href="{{ route('bookings.audit', $booking) }}" class="btn btn-outline-info">
                    {{ __('Audit Trail') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Tour') }}</th>
                            <th>{{ __('Adult') }}</th>
                            <th>{{ __('Child') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Discount') }}</th>
                            <th>{{ __('Total Price') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->package->tour->name }}</td>
                            <td>{{ $booking->adult }}</td>
                            <td>{{ $booking->child }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>RM {{ number_format($booking->discount) }}</td>
                            <td>RM {{ number_format($booking->total_price) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
