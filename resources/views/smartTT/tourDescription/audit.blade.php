@php
    /** @var \App\Models\TourDescription $tourDescription */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('Tour Description Management') }} - {{ config('app.name') }}
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
            <li class="breadcrumb-item active" aria-current="page">{{ __('Audit Trail') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Audit Trail') }}</h3>
        </div>
        <div class="card-body">
            @include('partials.audit-table')
        </div>
    </div>

@endsection
