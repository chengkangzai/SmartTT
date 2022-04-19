@php
/** @var \App\Models\Package $package */
/** @var \App\Models\Flight $flight */
@endphp

@extends('layouts.app')
@section('title')
    Package Management - {{ config('app.name') }}
@endsection
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('packages.index') }}">{{ __('Packages') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Show') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Package Information</h3>
            <div class="pull-right">
                <a href="{{ route('packages.edit', $package) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                <form action="{{ route('packages.destroy', $package) }}" method="POST" class="d-inline">
                    @method('DELETE')
                    @csrf
                    <input class="btn btn-danger" type="submit" value="{{ __('Delete') }}" />
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Fee') }}</th>
                            <th>{{ __('Departure') }}</th>
                            <th>{{ __('Capacity') }}</th>
                            <th>{{ __('Tour') }}</th>
                            <th>{{ __('Airline') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $package->id }}</td>
                            <td>RM {{ number_format($package->fee, 2) }}</td>
                            <td>{{ $package->depart_time }}</td>
                            <td>{{ $package->capacity }}</td>
                            <td>{{ $package->tour->name }}</td>
                            <td>
                                <ul>
                                    @foreach ($package->flight as $flight)
                                        <li>{{ $flight->airline->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Flight') }}</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Departure') }}</th>
                            <th>{{ __('Arrival') }}</th>
                            <th>{{ __('Airline') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($package->flight as $flight)
                            <tr>
                                <td>{{ $flight->id }}</td>
                                <td>{{ $flight->departure_date->toDayDateTimeString() }}
                                    {{ $flight->depart_airport->name }} </td>
                                <td>{{ $flight->arrival_date->toDayDateTimeString() }}
                                    {{ $flight->arrive_airport->name }} </td>
                                <td>{{ $flight->airline->name }}</td>
                                <td>
                                    <a href="{{ route('flights.show', $flight) }}" class="btn btn-info">
                                        {{ __('Show') }}
                                    </a>
                                    <a href="{{ route('flights.edit', $flight) }}" class="btn btn-primary">
                                        {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('flights.destroy', $flight) }}" class="d-inline"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input class="btn btn-danger" type="submit" value="{{ __('Delete') }}" />
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
