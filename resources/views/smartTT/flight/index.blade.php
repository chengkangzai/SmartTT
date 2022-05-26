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
            <li class="breadcrumb-item active" aria-current="page">{{ __('Flights') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <div class="float-end">
                @can('Create Flight')
                    <a href="{{ route('flights.create') }}" class="btn btn-outline-success">{{ __('Create') }}</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="indexTable" class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Depart Time') }}</th>
                            <th>{{ __('Arrival Time') }}</th>
                            <th>{{ __('Price') }}</th>
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
                                    @can('View Flight')
                                        <a href="{{ route('flights.show', $flight) }}" class="btn btn-outline-info">
                                            {{ __('Show') }}
                                        </a>
                                    @endcan
                                    @can('Edit Flight')
                                        <a href="{{ route('flights.edit', $flight) }}" class="btn btn-outline-primary">
                                            {{ __('Edit') }}
                                        </a>
                                    @endcan
                                    @can('Delete Flight')
                                        <form action="{{ route('flights.destroy', $flight) }}" method="POST"
                                            style="display: inline">
                                            @method('DELETE')
                                            @csrf
                                            <input class="btn btn-outline-danger" type="submit" value="{{ __('Delete') }}" />
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $flights->links() }}
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#indexTable').DataTable();
    </script>
@endpush
