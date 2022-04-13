@extends('layouts.app')
@section('title')
    Create Trip - {{ config('app.name') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('trips.index') }}">{{ __('Trips') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ __('Create Trip') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('trips.store') }}" method="POST" id="createForm">
                @include('partials.error-alert')
                @csrf
                <div class="mb-3">
                    <label for="fee" class="form-label">Fee (RM)</label>
                    <input type="number" name="fee" class="form-control" id="fee" value="{{ old('fee') }}"
                        placeholder="{{ __('Enter Trip Fee') }}">
                </div>
                <div class="mb-3">
                    <label for="capacity" class="form-label">Capacity</label>
                    <input type="number" name="capacity" class="form-control" id="capacity" value="{{ old('capacity') }}"
                        placeholder="{{ __('Enter Capacity of this trip') }}">
                </div>

                <div class="mb-3">
                    <label for="tour" class="form-label">Tour</label>
                    <select id="tour" name="tour" class="form-control">
                        @foreach ($tours as $tour)
                            <option value="{{ $tour->id }}"> {{ $tour->name }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="depart_time">Depart Time</label>
                    <input type="datetime-local" class="form-control" name="depart_time" id="depart_time" />
                </div>

                <div class="mb-3">
                    <label class="form-label" for="flightSelect">Flight</label>
                    <select name="flight[]" class="form-control select2 " id="flightSelect" multiple></select>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <input type="submit" class="btn btn-primary" value="{{ __('Submit') }}" form="createForm">
        </div>
    </div>
@endsection

@section('script')
    <script>
        $.ajax({
            type: "POST",
            url: "{{ route('select2.trip.getFlight') }}",
            success: function(response) {
                console.log(response);
                $("#flightSelect").empty();
                $("#flightSelect").select2({
                    data: response
                });
            }
        });
    </script>
@endsection
