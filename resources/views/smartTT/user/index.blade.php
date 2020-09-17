@extends('smartTT.layout.master')
@section('title')
    User Management - {{config('app.name')}}
@endsection
@section('cdn')
    <link rel="stylesheet" href="/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
@endsection

@section('content')
    <section class="content-header">
        <h1> <b>User Management</b> </h1>
        <ol class="breadcrumb">
            <li class="active"><a href="{{route('user.index')}}"><i class="fa fa-dashboard"></i> User </a></li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">User Management</h3>
                <div class="m-2 btn-group-vertical pull-right">
                    <a href="{{route('user.create')}}" class="btn btn-success">Create</a>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">

                    <table id="indexTable" class="table table-bordered table-hover ">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td>{{$user->name}}</td>

                                <td>
                                    <a href="{{route('user.show',['user'=>$user->id])}}" class="btn btn-info">Show</a>
                                    <a href="{{route('user.edit',['user'=>$user->id])}}"
                                       class="btn btn-primary">Edit</a>
                                    <form action="{{route('user.destroy',['user'=>$user->id])}}" style="display: inline"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input class="btn btn-danger" type="submit" value="Delete"/>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
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
