@php
/** @var \App\Models\Tour $tour */
/** @var \App\Models\Package $package */
@endphp
@extends('layouts.app')
@section('title')
    {{ __('Edit Package') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('packages.index') }}">{{ __('Packages') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card">
            <div class="card-header with-border">
                <h3 class="card-title">{{ __('Edit Package') }}</h3>
            </div>
            <div class="card-body">
                <form role="form" action="{{ route('packages.update', $package) }}" method="POST" id="editForm">
                    @include('partials.error-alert')
                    @method('PUT')
                    @csrf
                    <div class="mb-3 row">
                        <div class="col col-md-6">
                            <label for="tour" class="form-label">{{ __('Tour') }}</label>
                            <select name="tour_id" class="form-control" id="tour">
                                @foreach ($tours as $tour)
                                    <option value="{{ $tour->id }}" @selected(old('tour_id', $package->tour_id) == $tour->id)>
                                        {{ $tour->name }} ({{ $tour->tour_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col col-md-6">
                            <label for="depart_time" class="form-label">{{ __('Depart Time') }}</label>
                            <input type="datetime-local" class="form-control" name="depart_time" id="depart_time"
                                min="{{ date('Y-m-d\TH:i') }}"
                                value="{{ old('depart_time', $package->depart_time->format('Y-m-d\TH:i')) }}" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="flights">{{ __('Flight') }}</label>
                        <select name="flights[]" class="form-control select2 " id="flights" multiple>
                            @foreach ($flights as $flight)
                                <option value="{{ $flight->id }}" @selected($package->flight->contains($flight))>
                                    {{ $flight->asSelection }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button form="editForm" type="submit" class="btn btn-outline-primary">{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $("#flights").select2();
    </script>
@endsection
