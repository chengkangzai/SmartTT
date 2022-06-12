@extends('smartTT.layouts.app')

@section('title')
    {{ __('Sales Report') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Sales Report') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Sales Report') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.export', 'sales') }}" method="post" id="storeForm">
                @include('smartTT.partials.error-alert')
                @csrf
                <div class="mb-3 col-md-6">
                    <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                    <input type="date" id="start_date" name="start_date" class="form-control"
                        value="{{ now()->subMonths(3)->startOfMonth()->format('Y-m-d') }}" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                    <input type="date" id="end_date" name="end_date" class="form-control"
                        value="{{ now()->addMonth()->endOfMonth()->format('Y-m-d') }}" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="category" class="form-label">{{ __('Category') }}</label>
                    <select type="date" id="category" name="category" class="form-control">
                        <option value="">{{ __('All') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
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
