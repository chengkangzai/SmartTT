@extends('layouts.app')
@section('title')
    Edit Trip - {{ config('app.name') }}
@endsection
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('trips.index') }}">{{ __('Trips') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card">
            <div class="card-header with-border">
                <h3 class="card-title">{{ __('Edit Trip') }}</h3>
            </div>
            <div class="card-body">
                <form role="form" action="{{ route('trips.update', $trip) }}" method="POST" id="editForm">
                    @include('partials.error-alert')
                    @csrf
                    <div class="mb-3">
                        <label for="fee" class="form-label">Fee (RM)</label>
                        <input type="number" name="fee" class="form-control" id="fee"
                            value="{{ old('fee', $trip->fee / 100) }}" placeholder="{{ __('Enter Trip Fee') }}">
                    </div>
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control" id="capacity"
                            value="{{ old('capacity', $trip->capacity) }}"
                            placeholder="{{ __('Enter Capacity of this trip') }}">
                    </div>

                    <div class="mb-3">
                        <label for="tour" class="form-label">Tour</label>
                        <select name="tour" class="form-control" id="tour">
                            <option selected value="{{ $tour->id }}"> {{ $tour->name }} </option>
                            @foreach ($tours as $tour)
                                <option value="{{ $tour->id }}"> {{ $tour->name }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="depart_time" class="form-label">Depart Time</label>
                        <input type="datetime-local" class="form-control" name="depart_time" id="depart_time"
                            value="{{ old('depart_time', $trip->depart_time) }}" />
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button form="editForm" type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
@endsection
