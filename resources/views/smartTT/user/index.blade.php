@php
/** @var \App\Models\User $user */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('User Management') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Users') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <div class="float-end">
                <a href="{{ route('users.create') }}" class="btn btn-outline-success">{{ __('Create') }}</a>
            </div>
        </div>
        <div class="card-body">
            @include('partials.error-alert')
            <div class="table-responsive">
                <table id="indexTable" class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('User Name') }}</th>
                            <th>{{ __('User Email') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info">
                                        {{ __('Show') }}
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary">
                                        {{ __('Edit') }}
                                    </a>
                                    @can('Delete User')
                                        <form action="{{ route('users.destroy', $user) }}" class="d-inline"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input class="btn btn-outline-danger" type="submit" value="{{ __('Delete') }}" />
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#indexTable').DataTable();
    </script>
@endpush
