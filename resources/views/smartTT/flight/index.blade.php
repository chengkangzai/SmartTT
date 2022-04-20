@php
/** @var \App\Models\Flight $flight */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('Flight Management') }} - {{ config('app.name') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Flights') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <a href="{{ route('flights.create') }}" class="btn btn-success">{{ __('Create') }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="indexTable" class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Depart Time') }}</th>
                            <th>{{ __('Arrival Time') }}</th>
                            <th>{{ __('Price (RM)') }}</th>
                            <th>{{ __('Airline') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($flights as $flight)
                            <tr>
                                <td>{{ $flight->id }}</td>
                                <td>{{ $flight->departure_date->format(config('app.date_format')) }}</td>
                                <td>{{ $flight->arrival_date->format(config('app.date_format')) }}</td>
                                <td>{{ number_format($flight->price, 2) }}</td>
                                <td>{{ $flight->airline->name }}
                                    ({{ $flight->depart_airport->IATA }})
                                    -> ({{ $flight->arrive_airport->IATA }})
                                </td>
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
                <div class="card-footer">
                    {{ $flights->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#indexTable').DataTable();
    </script>
@endsection
