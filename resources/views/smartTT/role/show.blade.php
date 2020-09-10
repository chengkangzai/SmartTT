@extends('smartTT.layout.master')

@section('cdn')
    <link rel="stylesheet" href="/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">
@endsection
@section('title')
    Role : {{$role->name}}
@endsection

@section('content')
    <section class="content-header">
        <h1><b>User Role : {{$role->name}} </b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('role.index')}}"><i class="fa fa-dashboard"></i> User Role</a></li>
            <li class="active">Show</li>
        </ol>
    </section>

    <section class="content container-fluid w-75">


        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">User Role Information</h3>
                <div class="pull-right ">
                    <a href="{{route('role.edit',['role'=>$role->id])}}" class="btn btn-primary">Edit</a>
                    <form action="{{route('role.destroy',['role'=>$role->id])}}" style="display: inline" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="submit" role="button" value="Delete" class="btn btn-danger"/>
                    </form>
                </div>
            </div>
            <div class="box-body">
                <table class="table">
                    <thead>
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$role->id}}</td>
                        <td>{{$role->name}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">User with this role</h3>
                <div class="pull-right">
                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#addUserModal">Add</a>
                </div>
            </div>
            <div class="box-body">
                <table class="table" id="usersTable">
                    <thead>
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                                <a href="{{route('user.show',['user'=>$user->id])}}" class="btn btn-info">Show</a>
                                <form action="{{route('role.detachUserToRole',['role'=>$role->id])}}"
                                      style="display: inline;" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="text" name="user" value="{{$user->id}}" hidden/>
                                    <input type="submit" value="Detach" class="btn btn-danger">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Permission of this role</h3>
            </div>
            <div class="box-body">
                <table class="table" id="permissionTable">
                    <thead>
                    <tr>
                        <td>Name</td>
                        <td>Module</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($permissions as $permission)
                        <tr>
                            <td>{{$permission->name}}</td>
                            <td>{{$permission->module}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="modal fade" id="addUserModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add user to role:{{$role->name}}</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('role.attachUserToRole',['role'=>$role->id])}}" method="POST"
                              id="addUserForm">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="usersToBeAdd">User to be add</label>
                                <select class="form-control select2" id="usersToBeAdd" name="users[]"
                                        multiple required data-placeholder="Select User to add"
                                        style="width: 100%">
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="form-control btn btn-success" value="Submit">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script>
        $('#permissionTable').DataTable({
            "lengthMenu": [5, 10, 25, 50],
            "pageLength": 5
        });
        $('#usersTable').DataTable({
            "lengthMenu": [5, 10, 25, 50],
            "pageLength": 5
        });


        $.ajax({
            type: "POST",
            url: "{{route('select2.role.getUser')}}",
            data: {
                role_id:{{$role->id}}
            },
            success: function (response) {
                console.log(response);
                $("#usersToBeAdd").select2({data: response});
            }
        });
    </script>
@endsection
