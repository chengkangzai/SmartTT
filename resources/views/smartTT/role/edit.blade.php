@extends('layouts.app')

@section('title')
    {{ __('Edit Role') }} - {{ config('app.name') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('Roles') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ __('Edit User Role') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('roles.update', $role) }}" method="POST" id="editForm">
                @include('partials.error-alert')
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="name">{{ __('Name') }}</label>
                    <input type="text" name="name" class="form-control" id="name"
                        placeholder="{{ __('Enter User Role Name') }}" value="{{ old('name', $role->name) }}">
                </div>
                <div class="mb-3">
                    <label for="permissions" class="form-label">{{ __('Permissions') }}</label>
                    <select name="permissions[]" id="permissions" class="form-control" multiple>
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->id }}"
                                {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $permission->name }}
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
@endsection

@section('script')
    <script>
        $('#permissions').select2();
    </script>
@endsection
