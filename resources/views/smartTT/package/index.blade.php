@php
/** @var \App\Models\Package $package */
/** @var \App\Models\Airline $airline */
/** @var \App\Models\Settings\GeneralSetting $setting */
@endphp

@extends('smartTT.layouts.app')
@section('title')
    {{ __('Package Management') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Packages') }}</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header">
            <div class="float-end">
                @can('Create Package')
                    <a href="{{ route('packages.create') }}" class="btn btn-outline-success">{{ __('Create') }}</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <livewire:package-table />
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#indexTable').DataTable();
    </script>
@endpush
