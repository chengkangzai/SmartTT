@extends('smartTT.layout.master')

@section('content')
    <section class="content-header">
        <h1><b>Edit User Role : {{$role->name}} </b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('role.index')}}"><i class="fa fa-dashboard"></i> User Role</a></li>
            <li><a href="{{route('role.show',['role'=>$role->id])}}">{{$role->name}}</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <section class="content container-fluid w-75">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit User Role</h3>
            </div>
            <form role="form" action="{{route('role.update',['role'=>$role->id])}}" method="POST">
                @csrf
                @method('PUT')
                <div class="box-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name"
                               placeholder="Enter User Role Name" value="{{$role->name}}">
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </section>
@endsection
