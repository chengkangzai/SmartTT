@extends('layouts.app')
@section('title')
    Create Tour - {{ config('app.name') }}
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
                <div class="mb-3">
                    <label for="destination" class="form-label">{{ __('Destination') }}</label>
                    <input type="text" name="destination" class="form-control" id="destination"
                        value="{{ old('destination') }}" placeholder="{{ __('Enter Destination') }}">
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">{{ __('Category') }}</label>
                    <input type="text" name="category" class="form-control" id="category" value="{{ old('category') }}"
                        placeholder="{{ __('Enter Category') }}">
                </div>
                <div class="mb-3">
                    <label for="itinerary" class="form-label">{{ __('Itinerary') }}</label>
                    <input type="file" id="itinerary" name="itinerary" accept='application/pdf' class="form-control">
                </div>
                <div class="mb-3">
                    <label for="thumbnail" class="form-label">{{ __('Thumbnail') }}</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept='image/*' class="form-control">
                </div>
            </div>
        </div>

        <div class="card mt-2">
            <div class="card-header with-border">
                <h3 class="card-title">{{ __('Tour Description') }}</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="place.1" class="form-label">{{ __('Place 1') }}</label>
                    <input type="text" name="place[1]" class="form-control" id="place.1" value="{{ old('place[1]') }}"
                        placeholder="{{ __('Enter the main visit place 1') }}">
                </div>
                <div class="mb-3">
                    <label for="des.1" class="form-label">{{ __('Description 1') }}</label>
                    <textarea type="text" name="des[1]" class="form-control" id="des.1" rows="5"
                        placeholder="{{ __('Enter Description 1') }}">{{ old('des[1]') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="place.2" class="form-label">{{ __('Place 2') }}</label>
                    <input type="text" name="place[2]" class="form-control" id="place.2" value="{{ old('place[2]') }}"
                        placeholder="{{ __('Enter the main visit place 2') }}">
                </div>
                <div class="mb-3">
                    <label for="des.2" class="form-label">{{ __('Description 2') }}</label>
                    <textarea type="text" name="des[2]" class="form-control" id="des.2" rows="5"
                        placeholder="{{ __('Enter Description 2') }}">{{ old('des[2]') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="place.3" class="form-label">{{ __('Place 3') }}</label>
                    <input type="text" name="place[3]" class="form-control" id="place.3" value="{{ old('place[3]') }}"
                        placeholder="{{ __('Enter the main visit place 3') }}">
                </div>
                <div class="mb-3">
                    <label for="des.3" class="form-label">{{ __('Description 3') }}</label>
                    <textarea type="text" name="des[3]" class="form-control" id="des.3" rows="5"
                        placeholder="{{ __('Enter Description 3') }}">{{ old('des[3]') }}</textarea>
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
