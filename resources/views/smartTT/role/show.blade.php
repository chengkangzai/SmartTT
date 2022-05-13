@extends('layouts.app')
@section('title')
    {{ __('Roles') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('Roles') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Show') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('User Role Information') }}</h3>
            <div class="float-end">
                @can('Edit Role')
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline-primary">{{ __('Edit') }}</a>
                @endcan
                @can('Delete Role')
                    <form action="{{ route('roles.destroy', $role) }}" class="d-inline" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="submit" role="button" value="{{ __('Delete') }}" class="btn btn-outline-danger"/>
                    </form>
                @endcan
                @can('Delete Role')
                    <a href="{{ route('roles.audit', $role) }}" class="btn btn-outline-info">{{ __('Audit Trail') }}</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <td>{{ __('ID') }}</td>
                        <td>{{ __('Name') }}</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ __('User with this role') }}</h3>
            <div class="float-end">
                @can('Edit Role')
                    <button type="button" class="btn btn-outline-success" data-coreui-toggle="modal"
                            data-coreui-target="#addUserModal">
                        {{ __('Add') }}
                    </button>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table " id="usersTable">
                    <thead>
                    <tr>
                        <td>{{ __('ID') }}</td>
                        <td>{{ __('Name') }}</td>
                        <td>{{ __('Email') }}</td>
                        <td>{{ __('Action') }}</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @can('View Role')
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info">
                                        {{ __('Show') }}
                                    </a>
                                @endcan
                                @can('Edit Role')
                                    <form action="{{ route('roles.detachUserToRole', $role) }}" style="display: inline;"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="text" name="user_id" value="{{ $user->id }}" hidden/>
                                        <input type="submit" value="{{ __('Detach') }}" class="btn btn-outline-danger">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $users->links() }}</div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ __('Permission of the role') }} </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="permissionTable">
                    <thead>
                    <tr>
                        <td>{{ __('Name') }}</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $permissions->links() }}</div>
        </div>
    </div>
@endsection
@section('modal')
    @can('Edit Role')
        <div class="modal fade" id="addUserModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> {{ __('Add user to role') }}:{{ $role->name }}</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('roles.attachUserToRole', $role) }}" method="POST" id="addUserForm">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="usersToBeAdd">{{ __('User to be add') }}</label>
                                <select class="form-control select2" id="usersToBeAdd" name="users[]"
                                        style="width: 100%; z-index: 100000" multiple required
                                        data-placeholder="{{ __('Select User to add') }}">
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-coreui-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <input type="submit" form="addUserForm" class="btn btn-outline-primary"
                               value="{{ __('Save changes') }}">
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

@push('script')
    <script>
        $('#permissionTable').DataTable({
            "lengthMenu": [5, 10, 25, 50],
            "pageLength": 5,
            paging: false,
            info: false
        });
        $('#usersTable').DataTable({
            "lengthMenu": [5, 10, 25, 50],
            "pageLength": 5,
            paging: false,
            info: false
        });

        $.ajax({
            type: "POST",
            url: "{{ route('select2.role.getUser') }}",
            data: {
                role_id: {{ $role->id }}
            },
            success: function (response) {
                $("#usersToBeAdd").select2({
                    data: response,
                    dropdownParent: $("#addUserModal"),
                });
            }
        });
    </script>
@endpush
