@php
/** @var  \App\Models\Booking $booking */
@endphp
@section('title')
    {{ __('Home') }}
@endsection
@extends('layouts.app')

@section('content')
    <div class="container-lg">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">{{ __('My Tours') }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <td>{{ __('Tour Name') }}</td>
                        <td>{{ __('Depart Date') }}</td>
                        <td>{{ __('Total Price') }}</td>
                        <td>{{ __('Status') }}</td>
                        <td>{{ __('Action') }}</td>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $booking->package->tour->name }}</td>
                                <td>{{ $booking->package->depart_time }}</td>
                                <td>{{ number_format($booking->total_price,2) }}</td>
                                <td>{{ $booking->isFullPaid() ? __('Paid'): __('Pending') }}</td>
                                <td>
                                    @if($booking->isFullPaid())
                                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">{{ __('View') }}</a>
                                    @else
                                        <a href="{{ route('bookings.addPayment', $booking) }}" class="btn btn-sm btn-outline-primary">{{ __('Pay') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    {{__('What are you waiting?')}}
                                    {!! __('<a href=":link" class="btn btn-sm btn-outline-primary">Make a Booking Here!</a>', ['link' => route('bookings.create')]) !!}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{$bookings->links() }}
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">{{ __('My Payments') }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <td>{{ __('Method') }}</td>
                        <td>{{ __('Type') }}</td>
                        <td>{{ __('Status') }}</td>
                        <td>{{ __('Amount') }}</td>
                        <td>{{ __('Date') }}</td>
                    </thead>
                    <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td class="text-uppercase">{{ $payment->payment_method }}</td>
                            <td class="text-uppercase">{{ $payment->payment_type }}</td>
                            <td class="text-uppercase">{{ $payment->status }}</td>
                            <td>{{ number_format($payment->amount,2) }}</td>
                            <td>{{ $payment->created_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                {{__('No payment yet')}}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{$payments->links() }}
            </div>
        </div>
    </div>
@endsection
