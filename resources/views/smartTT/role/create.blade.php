@extends('smartTT.layouts.app')
@section('title')
    {{ __('Roles') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('Roles') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ __('Create User Role') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('roles.store') }}" method="POST" id="createForm">
                @include('smartTT.partials.error-alert')
                @csrf
                <div class=mb-3>
                    <label class="form-label" for="name">{{ __('Name') }}</label>
                    <input type="text" name="name" class="form-control" id="name"
                        placeholder="{{ __('User Role Name') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="permissions">{{ __('Permission') }}</label>
                    <select name="permissions[]" id="permissions" multiple class="form-control">
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button form="createForm" type="submit" class="btn btn-outline-primary">{{ __('Submit') }}</button>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#permissions').select2();
    </script>
@endpush
