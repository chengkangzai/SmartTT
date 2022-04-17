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
                <a href="{{ route('packages.edit', ['package' => $trip->id]) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('packages.destroy', ['package' => $trip->id]) }}" method="POST"
                    style="display: inline">
                    @method('DELETE')
                    @csrf
                    <input class="btn btn-danger" type="submit" value="Delete" />
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Package Fee</th>
                            <th>Package Departure</th>
                            <th>Package Capacity</th>
                            <th>Tour</th>
                            <th>Airline</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $trip->id }}</td>
                            <td>RM {{ number_format($trip->fee / 100, 2) }}</td>
                            <td>{{ $trip->depart_time }}</td>
                            <td>{{ $trip->capacity }}</td>
                            <td>{{ $trip->tour->name }}</td>
                            <td>{{ $trip->airline }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Package Flight</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Flight Departure</th>
                            <th>Flight Arrival</th>
                            <th>Airline</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($flights as $flight)
                            <tr>
                                <td>{{ $flight->id }}</td>
                                <td>{{ $flight->depart_time }}</td>
                                <td>{{ $flight->arrive_time }}</td>
                                <td>{{ $flight->airline()->first()->name }}</td>
                                <td>
                                    <a href="{{ route('flights.show', ['flight' => $flight->id]) }}"
                                        class="btn btn-info">Show</a>
                                    <a href="{{ route('flights.edit', ['flight' => $flight->id]) }}"
                                        class="btn btn-primary">Edit</a>
                                    <form action="{{ route('flights.destroy', ['flight' => $flight->id]) }}"
                                        style="display: inline" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input class="btn btn-danger" type="submit" value="Delete" />
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
