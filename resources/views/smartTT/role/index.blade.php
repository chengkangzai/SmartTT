@extends('smartTT.layouts.app')

@section('title')
    {{ __('Roles Management') }}
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
                @can('Create Role')
                    <a href="{{ route('roles.create') }}" class="btn btn-outline-success">{{ __('Create') }}</a>
                @endcan
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
                                    @can('View Role')
                                        <a href="{{ route('roles.show', $role) }}" class="btn btn-outline-info">
                                            {{ __('Show') }}
                                        </a>
                                    @endcan
                                    @can('Edit Role')
                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline-primary">
                                            {{ __('Edit') }}
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $roles->links() }}
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#indexTable').DataTable();
    </script>
@endpush
