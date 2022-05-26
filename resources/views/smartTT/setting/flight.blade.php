@php
/** @var \App\Models\Settings\FlightSetting $setting */
@endphp

@extends('layouts.app')

@section('title')
    {{ __('Flight Settings') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('Settings') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Flight Setting') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Flight Settings') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update', 'flight') }}" method="post" id="storeForm">
                @include('partials.error-alert')
                @csrf
                <div id="supported_class">
                    @foreach ($setting->supported_class as $class)
                        <input type="text" hidden name="supported_class[]" value="{{ $class }}">
                    @endforeach
                </div>
                <div id="supported_type">
                    @foreach ($setting->supported_type as $type)
                        <input type="text" hidden name="supported_type[]" value="{{ $type }}">
                    @endforeach
                </div>
                <div class="mb-3">
                    <label for="default_class"> {{ __('Default Class') }}
                        <a class="btn btn-sm btn-outline-primary my-2" data-coreui-toggle="modal"
                            data-coreui-target="#defaultClassModal" href="#">
                            {{ __('Create New Class') }}
                        </a>
                    </label>
                    <select name="default_class" id="default_class" class="form-select">
                        @foreach ($setting->supported_class as $class)
                            <option value="{{ $class }}" @selected(old('default_class', $setting->default_class) == $class)>
                                {{ $class }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="default_type">{{ __('Default Type') }}
                        <a class="btn btn-sm btn-outline-primary my-2" data-coreui-toggle="modal"
                            data-coreui-target="#defaultTypeModal" href="#">
                            {{ __('Create New Type') }}
                        </a>
                    </label>
                    <select name="default_type" id="default_type" class="form-select">
                        @foreach ($setting->supported_type as $type)
                            <option value="{{ $type }}" @selected(old('default_type', $setting->default_type) == $type)>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="supported_countries">{{ __('Default Country') }}</label>
                    <select name="supported_countries[]" id="supported_countries" class="form-select" multiple>
                        @foreach ($viewBag['countries'] as $country)
                            <option value="{{ $country->name }}" @selected(in_array($country->name, old('supported_countries', $setting->supported_countries)))>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline-primary" form="storeForm">
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="defaultClassModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="classModalLabel">{{ __('Add New class') }}</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form onsubmit="return addClass(event)" id="addClassForm">
                        <div class="form-group">
                            <label for="new_class">{{ __('Class') }}</label>
                            <input type="text" name="new_class" id="new_class" class="form-control"
                                placeholder="{{ __('Class') }}">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-coreui-dismiss="modal">{{ __('Close') }}</button>
                    <input type="submit" class="btn btn-primary" value="{{ __('Save changes') }}" form="addClassForm">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="defaultTypeModal" tabindex="-1" aria-labelledby="typeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="typeModalLabel">{{ __('Add New Type') }}</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form onsubmit="return addType(event)" id="addTypeForm">
                        <div class="form-group">
                            <label for="new_type">{{ __('Type') }}</label>
                            <input type="text" name="new_type" id="new_type" class="form-control"
                                placeholder="{{ __('Type') }}">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-coreui-dismiss="modal">{{ __('Close') }}</button>
                    <input type="submit" class="btn btn-primary" value="{{ __('Save changes') }}" form="addTypeForm">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#supported_countries').select2();

        function addClass(e) {
            e.preventDefault();

            let newClass = $('#new_class').val();
            $('#default_class').append(`<option value="${newClass}">${newClass}</option>`);
            $('#supported_class').append(`<input type="text" hidden name="supported_class[]" value="${newClass}" >`);

            $('#defaultClassModal').modal('hide');
        }

        function addType(e) {
            e.preventDefault();
            let newType = $('#new_type').val();
            $('#default_type').append(`<option value="${newType}">${newType}</option>`);
            $('#supported_type').append(`<input type="text" hidden name="supported_type[]" value="${newType}">`);

            $('#defaultTypeModal').modal('hide');
        }
    </script>
@endpush
