@extends('smartTT.layout.master')
@section('title')
    Dashboard - {{config('app.name')}}
@endsection

@section('content')
    <section class="content-header">
        <h1> <b>Create User Role</b> </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('role.index')}}"><i class="fa fa-dashboard"></i> User Role</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <section class="content container-fluid w-75">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Create User Role</h3>
            </div>
            <form role="form" action="{{route('role.store')}}" method="POST">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name"
                               placeholder="Enter User Role Name">
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </section>
    {{--TODO--}}
    {{--1. Tour Description--}}
@endsection