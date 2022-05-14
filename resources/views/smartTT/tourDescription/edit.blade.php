@php
/** @var \App\Models\TourDescription $tourDescription */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('Edit Tour Description') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('tours.show', $tourDescription->tour) }}">
                    {{ __('Tour') }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ __('Update') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('tourDescriptions.update', $tourDescription) }}" id="editForm"
                method="POST">
                @include('partials.error-alert')
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="place" class="form-label">Place </label>
                    <input type="text" name="place" class="form-control" id="place"
                        value="{{ $tourDescription->place }}" placeholder="{{ __('the main visit place') }}">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description </label>
                    <textarea type="text" name="description" class="form-control" id="description" rows="5"
                        placeholder="{{ __('Description') }}">{{ $tourDescription->description }}</textarea>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button form="editForm" type="submit" class="btn btn-outline-primary">{{ __('Submit') }}</button>
        </div>
    </div>
@endsection
