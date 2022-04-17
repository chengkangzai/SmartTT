@extends('layouts.app')
@section('title')
    Edit Flight - {{ config('app.name') }}
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
                @include('partials.error-alert')
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="depart_time">{{ __('Depart Time') }}</label>
                    <input type='datetime-local' class="form-control" name="depart_time" id="depart_time"
                        value="{{ old('depart_time', $flight->depart_time) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="arrive_time">{{ __('Arrive Time') }}</label>
                    <input type='datetime-local' class="form-control" name="arrive_time" id="arrive_time"
                        value="{{ old('arrive_time', $flight->arrive_time) }}" />
                </div>

                <div class="mb-3">
                    <label class="form-label" for="fee">{{ __('Fee') }}</label>
                    <input type="number" name="fee" class="form-control" id="fee"
                        value="{{ old('fee', $flight->fee / 100) }}" step="1" placeholder="Enter Flight Fee">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="airline_id">{{ __('Flight') }}</label>
                    <select name="airline_id" class="form-control select2 " id="airline_id" required>
                        <option value="0" disabled selected> {{ __('Please Select') }}</option>
                        @foreach ($airlines as $airline)
                            <option value="{{ $airline->id }}"
                                {{ $flight->airline->id === $airline->id ? 'selected' : '' }}> {{ $airline->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="depart_airport">{{ __('Depart Airport') }}</label>
                    <select name="depart_airport" class="form-control select2 " id="depart_airport" required>
                        <option value="0" disabled selected> {{ __('Please Select') }}</option>
                        @foreach ($airports as $airport)
                            <option value="{{ $airport->id }}"
                                {{ $flight->depart_airports->id === $airport->id ? 'selected' : '' }}>
                                {{ $airport->name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="mb-3">
                    <label class="form-label" for="arrival_airport">{{ __('Arrival Airport') }}</label>
                    <select name="arrival_airport" class="form-control select2 " id="arrival_airport" required>
                        <option value="0" disabled selected> {{ __('Please Select') }}</option>
                        @foreach ($airports as $airport)
                            <option value="{{ $airport->id }}"
                                {{ $flight->arrive_airport->id === $airport->id ? 'selected' : '' }}>
                                {{ $airport->name }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <div class="mb-3">
                    <label class="form-label" for="flight_class">{{ __('Flight Class') }}</label>
                    <select name="flight_class" class="form-control select2 " id="flight_class" required>
                        <option value="0" disabled selected> {{ __('Please Select') }}</option>
                        @foreach (\App\Models\Flight::FCLASS as $key => $class)
                            <option value="{{ $key }}"
                                {{ $flight->flight_class === $class ? 'selected' : '' }}>
                                {{ $class }} </option>
                        @endforeach
                    </select>

                </div>

                <div class="mb-3">
                    <label class="form-label" for="flight_type">{{ __('Flight Class') }}</label>
                    <select name="flight_type" class="form-control select2 " id="flight_type" required>
                        <option value="0" disabled selected> {{ __('Please Select') }}</option>
                        @foreach (\App\Models\Flight::TYPE as $key => $type)
                            <option value="{{ $key }}" {{ $flight->flight_type === $type ? 'selected' : '' }}>
                                {{ $type }} </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button form="editForm" type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#airline_id').select2();
        $('#depart_airport').select2();
        $('#arrival_airport').select2();
        $('#flight_class').select2();
        $('#flight_type').select2();
    </script>
@endsection
