@php
/** @var \App\Models\Settings\TourSetting $setting */
@endphp

@extends('smartTT.layouts.app')

@section('title')
    {{ __('Tour Settings') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('Settings') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Tour Setting') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Tour Settings') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update', 'tour') }}" method="post" id="storeForm">
                @include('smartTT.partials.error-alert')
                @csrf
                <div class="mb-3">
                    <label for="default_status">{{ trans('setting.tour.default_status') }}</label>
                    <select name="default_status" id="default_status" class="form-select">
                        <option value="0" @selected(old('default_status', $setting->default_status) == 0)>
                            {{ __('Inactive') }}
                        </option>
                        <option value="1" @selected(old('default_status', $setting->default_status) == 1)>
                            {{ __('Active') }}
                        </option>
                    </select>
                </div>
                <div class="mb-md-3 row">
                    <div class="col-12 col-md-6">
                        <label for="default_night">{{ trans('setting.tour.default_night') }}</label>
                        <input type="number" step="1" name="default_night" id="default_night" class="form-control"
                            value="{{ old('default_night', $setting->default_night) }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="default_day">{{ trans('setting.tour.default_day') }}</label>
                        <input type="number" step="1" name="default_day" id="default_day" class="form-control"
                            value="{{ old('default_day', $setting->default_day) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <p>
                        {{ __('Category') }}
                        <button type="button" class="btn btn-outline-primary btn-sm" data-coreui-toggle="modal"
                            data-coreui-target="#exampleModal">
                            {{ __('Add') }}
                        </button>
                    </p>
                    <div class="list-group" id="categoryList">
                        @foreach ($setting->category as $category)
                            <label class="list-group-item">
                                {{ $category }}
                                <input type="hidden" name="category[]" value="{{ $category }}">
                                <span class="badge bg-primary rounded-pill mx-2" data-coreui-toggle="tooltip"
                                    data-coreui-placement="right"
                                    title="{{ __('Total Tour that are using this category') }}">
                                    {{ $viewBag['tours'][$category] ?? '0' }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline-primary" form="storeForm">
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Tour Category') }}</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form onsubmit="return addTourCategory(event)" id="addCategoryForm">
                        <div class="form-group">
                            <label for="category">{{ trans('setting.tour.category') }}</label>
                            <input type="text" name="category" id="category" class="form-control"
                                placeholder="{{ __('Category') }}">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-coreui-dismiss="modal">{{ __('Close') }}</button>
                    <input type="submit" class="btn btn-primary" value="{{ __('Save changes') }}" form="addCategoryForm">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function addTourCategory(e) {
            e.preventDefault();
            let category = $('#category').val();
            $('#categoryList').append(`<label class="list-group-item">
                                            ${category}
                                            <input type="hidden" name="category[]" value="${category}">
                                            <span class="badge bg-success rounded-pill mx-2"
                                                  data-coreui-toggle="tooltip" data-coreui-placement="right"
                                                  title="{{ __('Newly added Category') }}">
                                                {{ __('New') }}
            </span>
        </label>`);
            $('#exampleModal').modal('hide');
        }
    </script>
@endpush
