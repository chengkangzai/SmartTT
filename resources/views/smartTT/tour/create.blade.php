@php
    /** @var \App\Models\Settings\TourSetting $setting */
@endphp
@extends('layouts.app')
@section('title')
    {{ __('Create Tour') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tours.index') }}">{{ __('Tour') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>

    <form role="form" action="{{ route('tours.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('partials.error-toast')
        <div class="card">
            <div class="card-header with-border">
                <h3 class="card-title">{{ __('Create Tour') }}</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}"
                           placeholder="{{ __('Enter Tour Name') }}">
                </div>
                <div class="mb-3">
                    <label for="tour_code" class="form-label">{{ __('Tour Code') }}</label>
                    <input type="text" name="tour_code" class="form-control" id="tour_code"
                           value="{{ old('tour_code') }}" placeholder="{{ __('Enter Tour Code') }}">
                </div>
                <div class="mb-3 row">
                    <div class="col col-md-6">
                        <label for="category" class="form-label">{{ __('Category') }}</label>
                        <select name="category" class="form-control" id="category">
                            @foreach ($setting->category as $category)
                                <option value="{{ $category }}" @selected(old('category', $category))>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col col-md-6">
                        <label for="country_id" class="form-label">{{ __('Country') }}</label>
                        <select name="countries[]" id="country_id" class="form-control py-3" multiple>
                            @foreach ($countries as $key => $country)
                                <option value="{{ $key }}"
                                    {{ in_array($key, old('countries', [])) ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col col-md-6">
                        <label for="days" class="form-label">{{ __('Days') }}</label>
                        <input type="number" name="days" class="form-control" id="days"
                               value="{{ old('days',$setting->default_day) }}"
                               placeholder="{{ __('Enter Days') }}">
                    </div>
                    <div class="col col-md-6">
                        <label for="nights" class="form-label">{{ __('Nights') }}</label>
                        <input type="number" name="nights" class="form-control" id="nights"
                               value="{{ old('nights',$setting->default_night) }}"
                               placeholder="{{ __('Enter Nights') }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col col-md-6">
                        <label for="itinerary" class="form-label">{{ __('Itinerary') }}</label>
                        <input type="file" id="itinerary" name="itinerary" accept='application/pdf'
                               class="form-control">
                    </div>
                    <div class="col col-md-6">
                        <label for="thumbnail" class="form-label">{{ __('Thumbnail') }}</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept='image/*' class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="is_active" class="form-check-label">{{ __('Active this Tour') }}</label>
                    <input type="checkbox" name="is_active" class="form-check-primary" id="is_active" value="1"
                        @checked(old('is_active', $setting->default_status))>
                </div>
            </div>
        </div>

        <div class="card mt-2">
            <div class="card-header with-border">
                <h3 class="card-title">{{ __('Tour Description') }}</h3>
            </div>
            <div class="card-body">
                <div class="mb-3 row">
                    <div class="col col-md-3">
                        <label for="place.1" class="form-label">{{ __('Place 1') }}</label>
                        <input type="text" name="place[1]" class="form-control" id="place.1"
                               value="{{ old('place.1') }}" placeholder="{{ __('Enter the main visit place 1') }}">
                    </div>
                    <div class="col col-md-9">
                        <label for="des.1" class="form-label">{{ __('Description 1') }}</label>
                        <textarea type="text" name="des[1]" class="form-control" id="des.1" rows="3"
                                  placeholder="{{ __('Enter Description 1') }}">{{ old('des.1') }}</textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col col-md-3">
                        <label for="place.2" class="form-label">{{ __('Place 2') }}</label>
                        <input type="text" name="place[2]" class="form-control" id="place.2"
                               value="{{ old('place.2') }}" placeholder="{{ __('Enter the main visit place 2') }}">
                    </div>
                    <div class="col col-md-9">
                        <label for="des.2" class="form-label">{{ __('Description 2') }}</label>
                        <textarea type="text" name="des[2]" class="form-control" id="des.2" rows="3"
                                  placeholder="{{ __('Enter Description 2') }}">{{ old('des.2') }}</textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col col-md-3">
                        <label for="place.3" class="form-label">{{ __('Place 3') }}</label>
                        <input type="text" name="place[3]" class="form-control" id="place.3"
                               value="{{ old('place.3') }}" placeholder="{{ __('Enter the main visit place 3') }}">
                    </div>
                    <div class="col col-md-9">
                        <label for="des.3" class="form-label">{{ __('Description 3') }}</label>
                        <textarea type="text" name="des[3]" class="form-control" id="des.3" rows="3"
                                  placeholder="{{ __('Enter Description 3') }}">{{ old('des.3') }}</textarea>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-outline-primary">{{ __('Submit') }}</button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        $('#country_id').select2();
    </script>
@endsection
