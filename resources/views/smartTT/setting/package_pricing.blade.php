@php
/** @var \App\Models\Settings\PackagePricingsSetting $setting */
@endphp

@extends('smartTT.layouts.app')

@section('title')
    {{ __('Package Pricing Settings') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">{{ __('Settings') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Package Pricing Setting') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Package Pricing Settings') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update', 'package_pricing') }}" method="post" id="storeForm">
                @include('smartTT.partials.error-alert')
                @csrf
                <div class="mb-3">
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-coreui-toggle="modal"
                            data-coreui-target="#exampleModal">
                            {{ __('Add') }}
                        </button>
                    </div>
                    <div class="list-group" id="namingList">
                        @foreach ($setting->default_namings as $key => $name)
                            <div class="mb-3 row">
                                <div class="col col-md-5">
                                    <label for="default_namings-{{ $key }}"
                                        class="form-label">{{ __('Name') }} </label>
                                    <input type="text" class="form-control" name="default_namings[{{ $key }}]"
                                        id="default_namings-{{ $key }}"
                                        value="{{ old('default_namings.' . $key, $name) }}" />
                                </div>
                                <div class="col col-md-4">
                                    <label for="default_capacity-{{ $key }}" class="form-label">
                                        {{ __('Total Capacity') }}
                                    </label>
                                    <input type="number" class="form-control"
                                        name="default_capacity[{{ $key }}]"
                                        id="default_capacity-{{ $key }}"
                                        value="{{ old('default_capacity.' . $key, $setting->default_capacity[$key]) }}"
                                        step="1">
                                </div>
                                <div class="col col-md-3">
                                    <label for="default_status-{{ $key }}" class="form-label">
                                        {{ __('Active this Pricing') }}
                                    </label>
                                    <input type="checkbox" class="form-check-input d-block"
                                        name="default_status[{{ $key }}]" id="default_status-{{ $key }}"
                                        value="1" {{ old('default_status.' . $key) == 1 ? 'checked' : '' }}>
                                </div>
                            </div>
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
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Package Pricing') }}</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form onsubmit="return addPackagePricing(event)" id="addPackagePricingForm">
                        <div class="mb-3">
                            <label for="default_namings" class="form-label">
                                {{ trans('setting.package_pricing.default_namings') }}
                            </label>
                            <input type="text" class="form-control" name="default_namings" id="default_namings"
                                value="{{ old('default_namings') }}" />
                        </div>
                        <div class="mb-3">
                            <label for="default_capacity" class="form-label">
                                {{ trans('setting.package_pricing.default_capacity') }}
                            </label>
                            <input type="number" class="form-control" name="default_capacity" id="default_capacity"
                                value="{{ old('default_capacity') }}" step="1">
                        </div>
                        <div class="mb-3">
                            <label for="default_status" class="form-label">
                                {{ trans('setting.package_pricing.default_status') }}
                            </label>
                            <input type="checkbox" class="form-check-input d-block" name="default_status"
                                id="default_status" value="1" {{ old('default_status') == 1 ? 'checked' : '' }}>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                    <input type="submit" class="btn btn-primary" value="{{ __('Save changes') }}"
                        form="addPackagePricingForm">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function addPackagePricing(e) {
            e.preventDefault();
            let defaultNaming = $('#default_namings').val();
            let defaultCapacity = $('#default_capacity').val();
            let defaultStatus = $('#default_status').val();

            //calculate how many row is in table namingList
            let rowCount = $('#namingList .row').length;

            $('#namingList').append(`
                 <div class="mb-3 row">
                                <div class="col col-md-5">
                                    <label for="default_namings" class="form-label">{{ __('Name') }} </label>
                                    <input type="text" class="form-control"
                                           name="default_namings[${rowCount}]" id="default_namings"
                                           value="${defaultNaming}"
                                    />
                                </div>
                                <div class="col col-md-4">
                                    <label for="default_capacity" class="form-label">
                                        {{ __('Total Capacity') }}
            </label>
            <input type="number" class="form-control"
                   name="default_capacity[${rowCount}]" id="default_capacity"
                                           value="${defaultCapacity}"
                                           step="1">
                                </div>
                                <div class="col col-md-3">
                                    <label for="default_status" class="form-label">
                                        {{ __('Active this Pricing') }}
            </label>
            <input type="checkbox" class="form-check-input d-block"
                   name="default_status[${rowCount}]" id="default_status"
                                           value="${defaultStatus}">
                                </div>
                            </div>
            `);
            $('#exampleModal').modal('hide');
        }
    </script>
@endpush
