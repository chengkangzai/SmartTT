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
            <li class="breadcrumb-item active" aria-current="page">{{ __('Bookings') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <div class="float-end">
                <a href="{{ route('bookings.create') }}" class="btn btn-outline-success">{{ __('Create') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="indexTable" class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Package') }}</th>
                            <th>{{ __('Payment Status') }}</th>
                            <th>{{ __('Made By') }} </th>
                            <th>{{ __('Total Price') }} ({{ $setting->default_currency }})</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>
                                    <a href="{{ route('packages.show', $booking->package) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        {{ $booking->package->tour->name }}
                                    </a>
                                </td>
                                <td>{{ $booking->isFullPaid() ? __('Paid') : __('Pending') }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ number_format($booking->total_price, 2) }}</td>
                                <td>
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-info">
                                        {{ __('Show') }}
                                    </a>
                                    <form action="{{ route('bookings.destroy', $booking) }}" class="d-inline"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input class="btn btn-outline-danger" type="submit" value="{{ __('Delete') }}" />
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#indexTable').DataTable();
    </script>
@endpush
