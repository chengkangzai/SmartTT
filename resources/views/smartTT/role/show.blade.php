@extends('smartTT.layout.master')

@section('content')
    <section class="content-header">
        <h1><b>User Role : {{$role->name}} </b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('role.index')}}"><i class="fa fa-dashboard"></i> User Role</a></li>
            <li class="active">Show</li>
        </ol>
    </section>

    <section class="content container-fluid w-75">
        <div class="mt-5 mb-5 btn-group">
            <a href="{{route('role.edit',['role'=>$role->id])}}" class="btn btn-lg btn-primary">Edit</a>
            <form action="{{route('role.destroy',['role'=>$role->id])}}" style="display: inline" method="POST">
                @method('DELETE')
                @csrf
                <input type="submit" role="button" value="Delete" class="btn btn-lg btn-danger"/>
            </form>
        </div>


        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">User Role Information</h3>
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
        {{-- TODO --}}
        {{-- 1.associated user --}}
        {{-- 2.Permissions user --}}
    </section>
@endsection
