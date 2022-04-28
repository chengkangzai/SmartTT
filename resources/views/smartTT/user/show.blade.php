@php
/** @var \App\Models\User $user */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('User Management') }} - {{ config('app.name') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('Users') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Show') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('User Information') }}</h3>
            <div class="pull-right">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                @can('Delete User')
                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline">
                        @method('DELETE')
                        @csrf
                        <input class="btn btn-danger text-white" type="submit" value="{{ __('Delete') }}" />
                    </form>
                @endcan
                <form action="{{ route('users.sendResetPassword', $user) }}" method="POST" style="display: inline">
                    @csrf
                    <input class="btn btn-info text-white" type="submit" value="{{ __('Send Password Reset Email') }}" />
                </form>
                <a href="{{ route('users.audit', $user) }}" class="btn btn-info text-white">{{ __('Audit Trail') }}</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('User Name') }}</th>
                        <th>{{ __('User Email') }}</th>
                        <th>{{ __('User Role') }}</th>
                        <th>{{ __('User Joined At') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{{ route('roles.show', $user->roles->first()) }}"
                                class="btn btn-outline-primary">{{ $user->roles->first()->name }}</a>
                        </td>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
