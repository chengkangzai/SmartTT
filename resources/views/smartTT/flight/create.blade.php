@extends('layouts.app')
@section('title')
    Create Flight - {{ config('app.name') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('flights.index') }}">{{ __('Flights') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ __('Create Flight') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('flights.store') }}" method="POST" id="createForm">
                @include('partials.error-alert')
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="depart_time">{{ __('Depart Time') }}</label>
                    <input type='datetime-local' class="form-control" name="depart_time" id="depart_time"
                        value="{{ old('depart_time', '') }}" />
                </div>

                <div class="mb-3">
                    <label class="form-label" for="arrive_time">{{ __('Arrive Time') }}</label>
                    <input type='datetime-local' class="form-control" name="arrive_time" id="arrive_time"
                        value="{{ old('arrive_time') }}" />
                </div>

                <div class="mb-3">
                    <label class="form-label" for="fee">{{ __('Fee') }}</label>
                    <input type="number" name="fee" class="form-control" id="fee" value="{{ old('fee', 0) }}" step="1"
                        placeholder="{{ __('Enter Flight Fee') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="airline_id">{{ __('Flight') }}</label>
                    <select name="airline_id" class="form-control select2 " id="airline_id" required>
                        @foreach ($airlines as $airline)
                            <option value="{{ $airline->id }}"> {{ $airline->name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="mb-3">
                    <label class="form-label" for="depart_airport">{{ __('Depart Airport') }}</label>
                    <select name="depart_airport" class="form-control select2 " id="depart_airport" required>
                        @foreach ($airports as $airport)
                            <option value="{{ $airport->id }}"> {{ $airport->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="arrival_airport">{{ __('Arrival Airport') }}</label>
                    <select name="arrival_airport" class="form-control select2 " id="arrival_airport" required>
                        @foreach ($airports as $airport)
                            <option value="{{ $airport->id }}"> {{ $airport->name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="mb-3">
                    <label class="form-label" for="flight_class">{{ __('Flight Class') }}</label>
                    <select name="flight_class" class="form-control select2" id="flight_class" required>
                        @foreach (\App\Models\Flight::FCLASS as $key => $class)
                            <option value="{{ $key }}"> {{ $class }} </option>
                        @endforeach
                    </select>

                </div>

                <div class="mb-3">
                    <label class="form-label" for="flight_type">{{ __('Flight Class') }}</label>
                    <select name="flight_type" class="form-control select2" id="flight_type" required>
                        @foreach (\App\Models\Flight::TYPE as $key => $type)
                            <option value="{{ $key }}"> {{ $type }} </option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="card-footer">
                <button form="createForm" type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
            </div>
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
