@extends('smartTT.layout.master')
@section('title')
    Edit User - {{config('app.name')}}
@endsection
@section('content')
    <section class="content-header">
        <h1><b>Edit User : {{$user->name}} </b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('user.index')}}"><i class="fa fa-dashboard"></i> User</a></li>
            <li><a href="{{route('user.show',['user'=>$user->id])}}">{{$user->name}}</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <section class="content container-fluid w-75">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit User</h3>
            </div>
            <form role="form" action="{{route('user.update',['user'=>$user->id])}}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="box-body">
                    <div class="form-group @error('name') has-error @enderror">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control " id="name"
                               value="{{$user->name}}" placeholder="Enter User Name">
                        @error('name')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('email') has-error @enderror">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email"
                               value="{{$user->email}}" placeholder="Enter Email">
                        @error('email')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <hr>
                    <div class="form-group @error('password') has-error @enderror">
                        <label for="password">Current Password</label>
                        <input type="password" name="password" class="form-control" id="password"
                               value="{{old('password')}}" placeholder="Enter Current Password">
                        @error('password')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </section>
    {{--    TODO upload avatar--}}
    {{--    Image optimizer ?--}}
@endsection
