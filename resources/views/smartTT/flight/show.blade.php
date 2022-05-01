@php
/** @var \App\Models\Flight $flight */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('Flight Management') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('flights.index') }}">{{ __('Flights') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Show') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Flight Information') }}</h3>
            <div class="float-end">
                <a href="{{ route('flights.edit', $flight) }}" class="btn btn-outline-primary">{{ __('Edit') }}</a>
                <form action="{{ route('flights.destroy', $flight) }}" method="POST" class="d-inline">
                    @method('DELETE')
                    @csrf
                    <input class="btn btn-outline-danger" type="submit" value="{{ __('Delete') }}" />
                </form>
                <a href="{{ route('flights.audit', $flight) }}"
                    class="btn btn-outline-info">{{ __('Audit Trail') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Depart Time') }}</th>
                            <th>{{ __('Arrival Time') }}</th>
                            <th>{{ __('Fee (Rm)') }}</th>
                            <th>{{ __('Airline') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Class') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $flight->id }}</td>
                            <td>{{ $flight->departure_date }}</td>
                            <td>{{ $flight->arrival_date }}</td>
                            <td>RM {{ number_format($flight->price, 2) }}</td>
                            <td>{{ $flight->airline->name }}
                                ({{ $flight->depart_airport->IATA }}) -> ({{ $flight->arrive_airport->IATA }})
                            </td>
                            <td>{{ $flight->type }}</td>
                            <td>{{ $flight->class }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
