@php
    /** @var \App\Models\Flight $flight */
    /** @var \App\Models\Settings\FlightSetting $setting */
@endphp

@extends('smartTT.layouts.app')
@section('title')
    {{ __('Edit Flight') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('flights.index') }}">{{ __('Flights') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ __('Edit Flight') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('flights.update', $flight) }}" method="POST" id="editForm">
                @include('smartTT.partials.error-alert')
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="airline_id" class="form-label">{{ __('Airline') }}</label>
                    <select class="form-control" id="airline_id" name="airline_id">
                        <option value="">{{ __('Please Select') }}</option>
                        @foreach ($airlines as $airline)
                            <option
                                value="{{ $airline->id }}" @selected(old('airline_id', $airline->id) == $flight->airline_id)>
                                {{ $airline->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 row">
                    <div class="col col-md-6">
                        <label class="form-label" for="departure_airport_id">{{ __('Departure Airport') }}</label>
                        <select name="departure_airport_id" class="form-control select2" id="departure_airport_id"
                                multiple>
                            <option selected value="{{ $flight->departure_airport_id }}">
                                {{ $flight->depart_airport->name }}
                            </option>
                        </select>
                    </div>
                    <div class="col col-md-6">
                        <label class="form-label" for="arrival_airport_id">{{ __('Arrival Airport') }}</label>
                        <select name="arrival_airport_id" class="form-control select2" id="arrival_airport_id" multiple>
                            <option selected value="{{ $flight->arrival_airport_id }}">
                                {{ $flight->arrive_airport->name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col col-md-6">
                        <label class="form-label" for="departure_date"> {{ __('Departure Time') }}
                            <small>({{ __('based on departure timezone') }})</small>
                        </label>
                        <input type='datetime-local' class="form-control" name="departure_date" id="departure_date"
                               min="{{ date('Y-m-d\TH:i') }}"
                               value="{{ old('departure_date', $flight->departure_date->format('Y-m-d\TH:i')) }}"/>
                    </div>
                    <div class="col col-md-6">
                        <label class="form-label" for="arrival_date"> {{ __('Arrival Time') }}
                            <small>({{ __('based on arrival timezone') }})</small>
                        </label>
                        <input type='datetime-local' class="form-control" name="arrival_date" id="arrival_date"
                               min="{{ date('Y-m-d\TH:i') }}"
                               value="{{ old('arrival_date', $flight->arrival_date->format('Y-m-d\TH:i')) }}"/>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="price">{{ __('Price') }}</label>
                    <input type="number" name="price" class="form-control" id="price"
                           value="{{ old('price', $flight->price) }}" step="1" placeholder="{{ __('Flight Price') }}">
                </div>


                <div class="mb-3">
                    <label class="form-label" for="class">{{ __('Flight Class') }}</label>
                    <select name="class" class="form-control" id="class">
                        @foreach ($setting->supported_class as $class)
                            <option value="{{ $class }}" @selected(old('class', $flight->class) == $class)>
                                {{ $class }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="type">{{ __('Flight Type') }}</label>
                    <select name="type" class="form-control" id="type">
                        @foreach ($setting->supported_type as $type)
                            <option
                                value="{{ $type }}" @selected(old('type', $flight->type) == $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button form="editForm" type="submit" class="btn btn-outline-primary">{{ __('Update') }}</button>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <script>
        const config = {
            maximumSelectionLength: 1,
            ajax: {
                url: '{{ route('select2.flights.getAirports') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.text,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
        }
        $('#departure_airport_id').select2(config);
        $('#arrival_airport_id').select2(config);
    </script>
@endpush
