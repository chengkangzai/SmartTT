@php
/** @var \App\Models\Tour $tour */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('Tour Management') }} - {{ config('app.name') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Tours') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <a href="{{ route('tours.create') }}" class="btn btn-success">{{ __('Create') }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="indexTable" class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Tour Name') }}</th>
                            <th>{{ __('Tour Code') }}</th>
                            <th>{{ __('Destination') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tours as $tour)
                            <tr>
                                <td>{{ $tour->id }}</td>
                                <td>{{ $tour->name }}</td>
                                <td>{{ $tour->tour_code }}</td>
                                <td>
                                    <ul>
                                        @foreach ($tour->countries as $country)
                                            <li>{{ $country->name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $tour->category }}</td>
                                <td>
                                    <a href="{{ route('tours.show', $tour) }}" class="btn btn-info">
                                        {{ __('Show') }}
                                    </a>
                                    <a href="{{ route('tours.edit', $tour) }}" class="btn btn-primary">
                                        {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('tours.destroy', $tour) }}" class="d-inline"
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
                {{ $tours->links() }}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#indexTable').DataTable();
    </script>
@endsection
