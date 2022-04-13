@extends('layouts.app')
@section('title')
    Trip Management - {{ config('app.name') }}
@endsection
@section('cdn')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.css') }}">
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Trips') }}</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header">
            <a href="{{ route('trips.create') }}" class="btn btn-success">Create</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="indexTable" class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Trip Fee</th>
                            <th>Trip Capacity</th>
                            <th>Trip Departure</th>
                            <th>Tour</th>
                            <th>Airline</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trips as $trip)
                            <tr>
                                <td>{{ $trip->id }}</td>
                                <td>RM{{ number_format($trip->fee / 100, 2) }}</td>
                                <td>{{ $trip->capacity }}</td>
                                <td>{{ $trip->depart_time->format(config('app.date_format')) }}</td>
                                <td>{{ $trip->tour->name }}</td>
                                <td>{{ $trip->airline }}</td>
                                <td>
                                    <a href="{{ route('trips.show', ['trip' => $trip->id]) }}" class="btn btn-info">Show</a>
                                    <a href="{{ route('trips.edit', ['trip' => $trip->id]) }}"
                                        class="btn btn-primary">Edit</a>
                                    <form action="{{ route('trips.destroy', ['trip' => $trip->id]) }}" style="display: inline"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input class="btn btn-danger" type="submit" value="Delete" />
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $trips->links() }}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#indexTable').DataTable({
            bInfo: false,
            paging: false,
        });
    </script>
@endsection
