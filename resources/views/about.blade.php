@extends('smartTT.layouts.app')
@section('title')
    {{ __('About') }}
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            {{ __('About us') }}
        </div>
        <div class="card-body">
            {{ __('Sample static text page') }}
        </div>
    </div>
@endsection
