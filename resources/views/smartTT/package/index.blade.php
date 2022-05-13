@php
    /** @var \App\Models\Package $package */
    /** @var \App\Models\Airline $airline */
    /** @var \App\Models\Settings\GeneralSetting $setting */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('Package Management') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Packages') }}</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header">
            <div class="float-end">
                @can('Create Package')
                    <a href="{{ route('packages.create') }}" class="btn btn-outline-success">{{ __('Create') }}</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="indexTable" class="table table-bordered table-hover ">
                    <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Departure') }}</th>
                        <th>{{ __('Tour') }}</th>
                        <th>{{ __('Airline') }}</th>
                        <th>{{ __('Price') }} ({{ $setting->default_currency }})</th>
                        <th>{{ __('Active') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($packages as $package)
                        <tr>
                            <td>{{ $package->id }}</td>
                            <td>{{ $package->depart_time->format(config('app.date_format')) }}</td>
                            <td>{{ $package->tour->name }}</td>
                            <td>
                                <ol>
                                    @foreach ($package->flight as $flight)
                                        <li>{{ $flight->airline->name }}</li>
                                    @endforeach
                                </ol>
                            </td>
                            <td>{{ __($package->price) }}</td>
                            <td>
                                <x-active-inactive-badge :active="$package->is_active"/>
                            </td>
                            <td>
                                @can('View Package')
                                    <a href="{{ route('packages.show', $package) }}" class="btn btn-outline-info">
                                        {{ __('Show') }}
                                    </a>
                                @endcan
                                @can('Edit Package')
                                    <a href="{{ route('packages.edit', $package) }}" class="btn btn-outline-primary">
                                        {{ __('Edit') }}
                                    </a>
                                @endcan
                                @can('Delete Package')
                                    <form action="{{ route('packages.destroy', $package) }}" class="d-inline"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input class="btn btn-outline-danger" type="submit" value="{{ __('Delete') }}"/>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $packages->links() }}
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#indexTable').DataTable();
    </script>
@endpush
