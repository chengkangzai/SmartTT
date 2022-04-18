@extends('layouts.app')
@section('title')
    Flight Management - {{ config('app.name') }}
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
                            <th>{{ __('Fee (Rm)') }}</th>
                            <th>{{ __('Airline') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($flights as $flight)
                            <tr>
                                <td>{{ $flight->id }}</td>
                                <td>{{ $flight->depart_time->format(config('app.date_format')) }}</td>
                                <td>{{ $flight->arrive_time->format(config('app.date_format')) }}</td>
                                <td>RM {{ number_format($flight->fee / 100, 2) }}</td>
                                <td>{{ $flight->airline->name }}
                                    ({{ $flight->depart_airports->ICAO }})
                                    -> ({{ $flight->arrive_airport->ICAO }})
                                </td>
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
