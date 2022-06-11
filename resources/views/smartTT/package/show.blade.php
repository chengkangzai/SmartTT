@php
/** @var \App\Models\Package $package */
/** @var \App\Models\Flight $flight */
/** @var \App\Models\PackagePricing $pricing */
@endphp

@extends('smartTT.layouts.app')
@section('title')
    {{ __('Package Management') }}
@endsection
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('packages.index') }}">{{ __('Packages') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Show') }}</li>
        </ol>
    </nav>

    <div class="card mb-1">
        <div class="card-header">
            <h3 class="card-title">{{ __('Package Information') }}</h3>
            <div class="float-end">
                @can('Edit Package')
                    <a href="{{ route('packages.edit', $package) }}" class="btn btn-outline-primary">{{ __('Edit') }}</a>
                @endcan
                @can('Delete Package')
                    <form action="{{ route('packages.destroy', $package) }}" method="POST" class="d-inline">
                        @method('DELETE')
                        @csrf
                        <input class="btn btn-outline-danger" type="submit" value="{{ __('Delete') }}" />
                    </form>
                @endcan
                @can('Audit Package')
                    <a href="{{ route('packages.audit', $package) }}" class="btn btn-outline-info">
                        {{ __('Audit Trail') }}
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Departure') }}</th>
                            <th>{{ __('Tour') }}</th>
                            <th>{{ __('Airline') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $package->id }}</td>
                            <td>{{ $package->depart_time->translatedFormat(config('app.date_format')) }}</td>
                            <td>{{ $package->tour->name }}</td>
                            <td>
                                <ul>
                                    @foreach ($package->flight as $flight)
                                        <li>{{ $flight->airline->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-1">
        <div class="card-header">
            <h3 class="card-title">{{ __('Pricing Plan') }}</h3>
            <div class="float-end">
                @can('Edit Package')
                    <a href="#" class="btn btn-outline-success" data-coreui-toggle="modal"
                        data-coreui-target="#addPackagePricingModal">
                        {{ __('Add') }}
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Pricing Name') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Total Capacity') }}</th>
                            <th>{{ __('Available Capacity') }}</th>
                            <th>{{ __('Active') }}</th>
                            @canany(['Edit Package Pricing', 'Delete Package Pricing', 'Audit Package Pricing'])
                                <th>{{ __('Action') }}</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($package->pricings as $pricing)
                            <tr>
                                <td>{{ $pricing->id }}</td>
                                <td>{{ $pricing->name }}</td>
                                <td>RM {{ number_format($pricing->price, 2) }}</td>
                                <td>{{ $pricing->total_capacity }}</td>
                                <td>{{ $pricing->available_capacity }}</td>
                                <td>{{ $pricing->is_active ? __('Active') : __('Inactive') }}</td>
                                @canany(['Edit Package Pricing', 'Delete Package Pricing', 'Audit Package Pricing'])
                                    <td>
                                        @can('Edit Package Pricing')
                                            <a href="{{ route('packagePricings.edit', $pricing) }}"
                                                class="btn btn-outline-primary">
                                                {{ __('Edit') }}
                                            </a>
                                        @endcan
                                        @can('Delete Package Pricing')
                                            @if ($pricing->guests->count() == 0)
                                                <form action="{{ route('packagePricings.destroy', $pricing) }}" method="POST"
                                                    class="d-inline">
                                                    @method('DELETE')
                                                    @csrf
                                                    <input class="btn btn-outline-danger" type="submit"
                                                        value="{{ __('Delete') }}" />
                                                </form>
                                            @endif
                                        @endcan
                                        @can('Audit Package Pricing')
                                            <a href="{{ route('packagePricings.audit', $pricing) }}"
                                                class="btn btn-outline-info">
                                                {{ __('Audit Trail') }}
                                            </a>
                                        @endcan
                                    </td>
                                @endcanany
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-1">
        <div class="card-header">
            <h3 class="card-title">{{ __('Flight') }}</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Departure') }}</th>
                            <th>{{ __('Departure Airport') }}</th>
                            <th>{{ __('Arrival') }}</th>
                            <th>{{ __('Arrival Airport') }}</th>
                            <th>{{ __('Airline') }}</th>
                            @canany(['Show flight', 'Edit flight', 'Delete flight'])
                                <th>{{ __('Action') }}</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($package->flight as $flight)
                            <tr>
                                <td>{{ $flight->id }}</td>
                                <td>{{ $flight->departure_date->translatedFormat(config('app.date_format')) }}</td>
                                <td>{{ $flight->depart_airport->name }}</td>
                                <td>{{ $flight->arrival_date->translatedFormat(config('app.date_format')) }}</td>
                                <td>{{ $flight->arrive_airport->name }}</td>
                                <td>{{ $flight->airline->name }}</td>
                                @canany(['Show flight', 'Edit flight', 'Delete flight'])
                                    <td>
                                        @can('Show Flight')
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
                                            <form action="{{ route('flights.destroy', $flight) }}" class="d-inline"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input class="btn btn-outline-danger" type="submit" value="{{ __('Delete') }}" />
                                            </form>
                                        @endcan
                                    </td>
                                @endcanany
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('smartTT.partials.error-toast')
@endsection


@section('modal')
    @can('Edit Package')
        <div class="modal fade" tabindex="-1" id="addPackagePricingModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Add New Package Pricing') }}</h4>
                        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('packagePricings.attach', $package) }}" method="POST"
                            id="addPackagePricingForm">
                            @csrf
                            @method('POST')
                            @include('smartTT.partials.error-toast')
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Pricing Name') }}</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="{{ __('Pricing Name') }}" value="{{ old('name') }}">
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">{{ __('Price') }}</label>
                                <input type="number" class="form-control" name="price" id="price"
                                    placeholder="{{ __('Price for Pricing') }}" value="{{ old('price') }}" step="0.01">
                            </div>
                            <div class="mb-3">
                                <label for="total_capacity" class="form-label">{{ __('Total Capacity') }}</label>
                                <input type="number" class="form-control" name="total_capacity" id="total_capacity"
                                    placeholder="{{ __('Total Capacity of') }}" value="{{ old('total_capacity') }}"
                                    step="1">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left"
                            data-coreui-dismiss="modal">{{ __('Close') }}</button>
                        <input form="addPackagePricingForm" type="submit" class="btn btn-outline-primary"
                            value="{{ __('Submit') }}" />
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
