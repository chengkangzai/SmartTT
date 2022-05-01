@php
/** @var \App\Models\Tour $tour */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('Edit Tour') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tours.index') }}">{{ __('Tour') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Edit Tour') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('tours.update', $tour) }}" method="POST" id="editForm"
                enctype="multipart/form-data">
                @include('partials.error-alert')
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $tour->name) }}"
                        placeholder="{{ __('Enter Tour Name') }}">
                </div>
                <div class="mb-3">
                    <label for="tour_code" class="form-label">{{ __('Tour Code') }}</label>
                    <input type="text" name="tour_code" class="form-control" id="tour_code"
                        value="{{ old('tour_code', $tour->tour_code) }}" placeholder="{{ __('Enter Tour Code') }}">
                </div>
                <div class="mb-3 row">
                    <div class="col col-md-6">
                        <label for="category" class="form-label">{{ __('Category') }}</label>
                        <input type="text" name="category" class="form-control" id="category"
                            value="{{ old('category', $tour->category) }}" placeholder="{{ __('Enter Category') }}">
                    </div>
                    <div class="col col-md-6">
                        <label for="country_id" class="form-label">{{ __('Country') }}</label>
                        <select name="countries[]" id="country_id" class="form-control py-3" multiple>
                            @foreach ($countries as $key => $country)
                                <option value="{{ $key }}"
                                    {{ in_array($key, old('countries', $tour->countries->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col col-md-6">
                        <label for="days" class="form-label">{{ __('Days') }}</label>
                        <input type="number" name="days" class="form-control" id="days" min="1"
                            value="{{ old('days', $tour->days) }}" placeholder="{{ __('Enter Days') }}">
                    </div>
                    <div class="col col-md-6">
                        <label for="nights" class="form-label">{{ __('Nights') }}</label>
                        <input type="number" name="nights" class="form-control" id="nights" min="1"
                            value="{{ old('nights', $tour->nights) }}" placeholder="{{ __('Enter Nights') }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col col-md-6">
                        <label for="itinerary" class="form-label">{{ __('Itinerary') }}</label>
                        <input type="file" id="itinerary" name="itinerary" accept='application/pdf' class="form-control">
                    </div>
                    <div class="col col-md-6">
                        <label for="thumbnail" class="form-label">{{ __('Thumbnail') }}</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept='image/*' class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="is_active" class="form-check-label">{{ __('Active this Tour') }}</label>
                    <input type="checkbox" name="is_active" class="form-check-primary" id="is_active" value="1"
                        @checked(old('is_active', $tour->is_active))>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button form="editForm" type="submit" class="btn btn-outline-primary">{{ __('Submit') }}</button>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#country_id').select2();
    </script>
@endpush
