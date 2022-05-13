@php
/** @var \App\Models\Tour $tour */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('Tour Management') }}
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
            <h2>{{ __('Tours') }}</h2>
            <div class="float-end">
                @can('Create Tour')
                    <a href="{{ route('tours.create') }}" class="btn btn-outline-success">{{ __('Create') }}</a>
                @endcan
            </div>
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
                            <th>{{ __('Active') }}</th>
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
                                    <x-active-inactive-badge :active="$tour->is_active" />
                                </td>
                                <td>
                                    @can('View Tour')
                                        <a href="{{ route('tours.show', $tour) }}" class="btn btn-outline-info">
                                            {{ __('Show') }}
                                        </a>
                                    @endcan
                                    @can('Edit Tour')
                                        <a href="{{ route('tours.edit', $tour) }}" class="btn btn-outline-primary">
                                            {{ __('Edit') }}
                                        </a>
                                    @endcan
                                    @can('Delete Tour')
                                        <form action="{{ route('tours.destroy', $tour) }}" class="d-inline"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input class="btn btn-outline-danger" type="submit" value="{{ __('Delete') }}" />
                                        </form>
                                    @endcan
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

@push('script')
    <script>
        $('#indexTable').DataTable();
    </script>
@endpush
