@php
/** @var \App\Models\User $user */
@endphp

@extends('smartTT.layouts.app')
@section('title')
    {{ __('User Management') }}
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
            <div class="float-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-coreui-toggle="dropdown"
                        aria-expanded="false">
                        {{ __('Action') }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                        @can('Edit User')
                            <li>
                                <a href="{{ route('users.edit', $user) }}" class="dropdown-item">{{ __('Edit') }}</a>
                            </li>
                        @endcan
                        @can('Delete User')
                            <li>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                    @method('DELETE')
                                    @csrf
                                    <input class="dropdown-item" type="submit" value="{{ __('Delete') }}" />
                                </form>
                            </li>
                        @endcan
                        @can('Edit User')
                            <li>
                                <form action="{{ route('users.sendResetPassword', $user) }}" method="POST"
                                    style="display: inline">
                                    @csrf
                                    <input class="dropdown-item" type="submit"
                                        value="{{ __('Send Password Reset Email') }}" />
                                </form>
                            </li>
                        @endcan
                        @can('Audit User')
                            <li>
                                <a href="{{ route('users.audit', $user) }}" class="dropdown-item">
                                    {{ __('Audit Trail') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
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
                            <a href="{{ route('roles.show', $user->roles->first()) }}" class="btn btn-outline-primary">
                                {{ $user->roles->first()->name }}
                            </a>
                        </td>
                        <td>{{ $user->created_at->translatedFormat(config('app.date_format')) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
