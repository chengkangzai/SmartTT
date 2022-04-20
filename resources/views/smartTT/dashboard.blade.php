@extends('layouts.app')
@section('title')
    {{ __('Dashboard') }} - {{ config('app.name') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Dashboard') }}</h3>
        </div>
        <div class="card-body">
            {{ __('Nothing here....') }}
        </div>
    </div>
@endsection
