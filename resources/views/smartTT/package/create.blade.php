@extends('layouts.app')
@section('title')
    Create Package - {{ config('app.name') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('packages.index') }}">{{ __('Packages') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ __('Create Package') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('packages.store') }}" method="POST" id="createForm">
                @include('partials.error-alert')
                @csrf
                <div class="mb-3 row">
                    <div class="col col-md-6">
                        <label for="price" class="form-label">{{ __('Price (RM)') }}</label>
                        <input type="number" name="price" class="form-control" id="price" value="{{ old('price') }}"
                            placeholder="{{ __('Enter Package Price') }}">
                    </div>
                    <div class="col col-md-6">
                        <label for="capacity" class="form-label">{{ __('Capacity') }}</label>
                        <input type="number" name="capacity" class="form-control" id="capacity"
                            value="{{ old('capacity') }}" placeholder="{{ __('Enter Capacity of this package') }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col col-md-6">
                        <label for="tour_id" class="form-label">{{ __('Tour') }}</label>
                        <select id="tour_id" name="tour_id" class="form-control">
                            @foreach ($tours as $tour)
                                <option value="{{ $tour->id }}" @selected(old('tour_id') == $tour->id)>
                                    {{ $tour->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col col-md-6">
                        <label class="form-label" for="depart_time">{{ __('Depart Time') }}</label>
                        <input type="datetime-local" class="form-control" name="depart_time" id="depart_time"
                            min="{{ date('Y-m-d\TH:i') }}"
                            value="{{ old('depart_time', now()->format('Y-m-d\TH:i')) }}" />
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="flights">{{ __('Flight') }}</label>
                    <select name="flights[]" class="form-control select2" id="flights" multiple>
                        @foreach ($flights as $flight)
                            <option value="{{ $flight->id }}" @selected(old('flights') == $flight->id)>
                                {{ $flight->text }}
                            </option>
                        @endforeach
                    </select>
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
        $("#flights").select2();
    </script>
@endsection
