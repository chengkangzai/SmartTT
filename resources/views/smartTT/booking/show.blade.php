@php
/** @var \App\Models\Booking $booking */
/** @var \App\Models\Payment $payment */
/** @var \App\Models\Settings\GeneralSetting $setting */
/** @var \App\Models\Settings\BookingSetting $bookingSetting */
@endphp
@extends('smartTT.layouts.app')
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

    <div class="card mb-2">
        <div class="card-header">
            <h3 class="card-title">{{ __('Booking Information') }}</h3>
            <div class="float-end">
                @can('Delete Booking')
                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST" style="display: inline">
                        @method('DELETE')
                        @csrf
                        <input class="btn btn-outline-danger" type="submit" value="{{ __('Delete') }}" />
                    </form>
                @endcan
                @can('Audit Booking')
                    <a href="{{ route('bookings.audit', $booking) }}" class="btn btn-outline-info">
                        {{ __('Audit Trail') }}
                    </a>
                @endcan
                @can('Sync booking to MS Calendar')
                    <a href="{{ route('bookings.sync', $booking) }}" class="btn btn-outline-primary">
                        {{ __('Sync this booking to my calendar') }}
                    </a>
                @endcan
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
                            <th>{{ __('Discount') }} ({{ $setting->default_currency }})</th>
                            <th>{{ __('Total Price') }} ({{ $setting->default_currency }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->package->tour->name }}</td>
                            <td>{{ $booking->adult }}</td>
                            <td>{{ $booking->child }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ number_format($booking->discount, 2) }}</td>
                            <td>{{ number_format($booking->total_price, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-2">
        <div class="card-header">
            <h3 class="card-title">{{ __('Booking Payment') }}</h3>
            @can('Create Payment')
                @if (!$booking->isFullPaid())
                    <div class="float-end">
                        <a href="{{ route('bookings.addPayment', $booking) }}" class="btn btn-outline-primary">
                            {{ __('Pay Remaining') }}
                        </a>
                    </div>
                @endif
            @endcan
        </div>
        <div class="card-body">
            <table class="table table-responsive">
                <tr>
                    <th>{{ __('Payment Date') }}</th>
                    <th>{{ __('Payment Method') }}</th>
                    <th>{{ __('Payment Status') }}</th>
                    <th>{{ __('Payment Type') }}</th>
                    <th>{{ __('Payment Amount') }} ({{ $setting->default_currency }})</th>
                    <th>{{ __('Invoice') }}</th>
                    <th>{{ __('Receipt') }}</th>
                </tr>
                @foreach ($booking->payment as $payment)
                    <tr>
                        <td>{{ $payment->created_at->translatedFormat(config('app.date_format')) }}</td>
                        <td class="text-uppercase">{{ $payment->payment_method }}</td>
                        <td class="text-uppercase">{{ $payment->status }}</td>
                        <td class="text-uppercase">{{ $payment->payment_type }}</td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                        <td>
                            <a target="_blank" class="btn btn-outline-primary"
                                href="{{ $payment->getFirstMediaUrl('invoices') ?? '#' }}">
                                {{ __('View') }}
                            </a>
                        </td>
                        <td>
                            @if ($payment->getFirstMediaUrl('receipts'))
                                <a target="_blank" class="btn btn-outline-primary"
                                    href="{{ $payment->getFirstMediaUrl('receipts') ?? '#' }}">
                                    {{ __('View') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="card mb-2">
        <div class="card-header">
            <h3 class="card-title">{{ __('Guests') }}</h3>
        </div>
        <div class="card-body">
            <table class="table table-responsive">
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Pricing Plan') }}</th>
                    <th>{{ __('Is Child') }}</th>
                </tr>
                @foreach ($booking->guests as $guest)
                    <tr>
                        <td>{{ $guest->name }}</td>
                        <td>
                            {{ number_format($guest->packagePricing?->price ?: $bookingSetting->charge_per_child, 2) }}
                        </td>
                        <td>{{ $guest->packagePricing->name ?? 'N/A' }}</td>
                        <td>{{ $guest->is_child ? 'Child' : 'Adult' }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    @can('View Tour')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Tour and Packages') }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-responsive">
                    <tr>
                        <th>{{ __('Tour Code') }}</th>
                        <th>{{ __('Destination') }}</th>
                        <th>{{ __('Departure Date') }}</th>
                        <th>{{ __('Itinerary') }}</th>
                    </tr>
                    <tr>
                        <td>
                            <a class="btn btn-outline-primary" href="{{ route('tours.show', $booking->package->tour) }}">
                                {{ $booking->package->tour->tour_code }}
                            </a>
                        </td>
                        <td>
                            <ul>
                                @foreach ($booking->package->tour->countries as $destination)
                                    <li>{{ $destination->name }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $booking->package->depart_time->translatedFormat(config('app.date_format')) }}</td>
                        <td>
                            <a class="btn btn-outline-primary"
                                href="{{ $booking->package->tour->getFirstMediaUrl('itinerary') ?? '#' }}">
                                {{ __('Itinerary') }}
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endcan
@endsection
