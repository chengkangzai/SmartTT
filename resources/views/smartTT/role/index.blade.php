@extends('smartTT.layout.master')

@section('content')
    <section class="content-header">
        <h1><b>User Role Management</b></h1>
        <ol class="breadcrumb">
            <li class="active"><a href="{{route('role.index')}}"><i class="fa fa-dashboard"></i> User Role</a></li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">User Role </h3>
                <div class="m-2 btn-group-vertical pull-right">
                    <a href="{{route('role.create')}}" class="btn btn-success">Create</a>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
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
                                    <a href="{{route('role.edit',['role'=>$role->id])}}"
                                       class="btn btn-primary">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{$roles->links()}}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')

    <script>
        $('#indexTable').DataTable({
            bInfo: false,
            paging: false,
        });
    </script>
@endsection
