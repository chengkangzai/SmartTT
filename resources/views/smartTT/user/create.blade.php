@extends('smartTT.layout.master')
@section('title')
    Create User - {{config('app.name')}}
@endsection

@section('content')
    <section class="content-header">
        <h1><b>Create User</b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('user.index')}}"><i class="fa fa-dashboard"></i> User</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <section class="content container-fluid w-75">
        <form role="form" action="{{route('user.store')}}" method="POST" enctype="multipart/form-data">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Create User</h3>
                </div>
                @csrf
                <div class="box-body">
                    <div class="form-group @error('name') has-error @enderror">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name"
                               value="{{old('name')}}" placeholder="Enter User Name">
                        @error('name')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('email') has-error @enderror">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email"
                               value="{{old('email')}}" placeholder="Enter Email">
                        @error('email')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('password') has-error @enderror">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password"
                               value="{{old('password')}}" placeholder="Enter Password">
                        @error('password')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('password_confirmation') has-error @enderror">
                        <label for="password_confirmation">Confirm your password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               id="password_confirmation"
                               value="{{old('password_confirmation')}}" placeholder="Enter Confirm your password">
                        @error('password_confirmation')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </section>
@endsection
