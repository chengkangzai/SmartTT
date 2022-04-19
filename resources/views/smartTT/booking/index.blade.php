@extends('layouts.app')
@section('title')
    Booking Management - {{ config('app.name') }}
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
            <a href="{{ route('bookings.create') }}" class="btn btn-success">{{ __('Create') }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="indexTable" class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Package') }}</th>
                            <th>{{ __('Adult') }}</th>
                            <th>{{ __('Child') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Discount') }}</th>
                            <th>{{ __('Total Price') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>
                                    <a href="{{ route('packages.show', $booking->packages) }}"
                                        class="btn btn-sm btn-primary">
                                        {{ $booking->packages->tour->name }}
                                    </a>
                                </td>
                                <td>{{ $booking->adult }}</td>
                                <td>{{ $booking->child }}</td>
                                <td>{{ $booking->users->name }}</td>
                                <td>RM {{ number_format($booking->discount, 2) }}</td>
                                <td>RM {{ number_format($booking->total_price, 2) }}</td>
                                <td>
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-info">
                                        {{ __('Show') }}
                                    </a>
                                    <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-primary">
                                        {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('bookings.destroy', $booking) }}" class="d-inline"
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
                    {{ $bookings->links() }}
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
