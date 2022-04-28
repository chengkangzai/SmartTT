@extends('layouts.app')

@section('title')
    {{ __('Roles Management') }} - {{ config('app.name') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Roles') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <div class="float-end">
                <a href="{{ route('roles.create') }}" class="btn btn-success">{{ __('Create') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="indexTable" class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Role Name') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <a href="{{ route('roles.show', $role) }}" class="btn btn-info">
                                        {{ __('Show') }}
                                    </a>
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary">
                                        {{ __('Edit') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#indexTable').DataTable();
    </script>
@endsection
