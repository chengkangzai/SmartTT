@extends('layouts.app')
@section('title')
    Edit User - {{config('app.name')}}
@endsection
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('users.index')}}">{{__('Users')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Edit')}}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{__('Edit User')}}</h3>
        </div>
        <form role="form" action="{{route('users.update',['user'=>$user->id])}}" method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="name">{{__('Name')}}</label>
                    <input type="text" name="name" class="form-control " id="name"
                           value="{{$user->name}}" placeholder="{{__('Enter User Name')}}">
                    @error('name')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">{{__('Email')}}</label>
                    <input type="email" name="email" class="form-control" id="email"
                           value="{{$user->email}}" placeholder="{{__('Enter Email')}}">
                    @error('email')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label" for="password">{{__('Current Password')}}</label>
                    <input type="password" name="password" class="form-control" id="password"
                           value="{{old('password')}}" placeholder="{{__('Enter Current Password')}}">
                    @error('password')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
            </div>
        </form>
    </div>
    {{--    TODO upload avatar--}}
    {{--    Image optimizer ?--}}
@endsection
