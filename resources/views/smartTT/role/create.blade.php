@extends('layouts.app')

@section('title')
    Roles - {{ config('app.name') }}
@endsection


@section('content')
    <section class="content-header">
        <h1><b>Create User Role</b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('roles.index')}}"><i class="fa fa-dashboard"></i> User Role</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">Create User Role</h3>
        </div>
        <form role="form" action="{{route('roles.store')}}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" id="name"
                           placeholder="Enter User Role Name">
                </div>
                <div class="form-group">
                    <label>Permission</label>
                    @foreach($permissions as $permission)
                        <h4>{{$permission->module}}</h4>
                        <label for="permissions">
                            <input type="checkcard" name="permissions[]" class="checkmark-circled"
                                   value="{{$permission->id}}">
                            {{$permission->name}} </label>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection
