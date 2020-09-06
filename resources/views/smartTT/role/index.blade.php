@extends('smartTT.layout.master')

@section('cdn')
    <link rel="stylesheet" href="/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
@endsection

@section('content')
    <section class="content-header">
        <h1><b>User Role Management</b></h1>
        <ol class="breadcrumb">
            <li class="active"><a href="{{route('role.index')}}"><i class="fa fa-dashboard"></i> User Role</a></li>
        </ol>
    </section>

    <section class="content container-fluid w-75">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">User Role </h3>
                <div class="m-2 btn-group-vertical pull-right">
                    <a href="{{route('role.create')}}" class="btn btn-success">Create</a>
                </div>
            </div>
            <div class="box-body">
                <table id="indexTable" class="table table-bordered table-hover ">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Role Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{$role->id}}</td>
                            <td>{{$role->name}}</td>
                            <td>
                                <a href="{{route('role.show',['role'=>$role->id])}}" class="btn btn-info">Show</a>
                                <a href="{{route('role.edit',['role'=>$role->id])}}" class="btn btn-primary">Edit</a>
                                <form action="{{route('role.destroy',['role'=>$role->id])}}" style="display: inline" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <input type="submit" role="button" value="Delete" class="btn btn-danger"/>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script>
        $('#indexTable').DataTable();
    </script>
@endsection
