@php
    /** @var \App\Models\User $user */
@endphp

@extends('smartTT.layouts.app')
@section('title')
    {{ __('Edit User') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('Users') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit User Role') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ __('Edit User') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('users.updateRole', $user) }}" method="POST" id="editForm">
                @include('smartTT.partials.error-alert')
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="role">{{ __('Role') }}</label>
                    <select class="form-control" id="role" name="role">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" @selected($user->hasRole($role->name))>
                                {{ $role->name }}
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
