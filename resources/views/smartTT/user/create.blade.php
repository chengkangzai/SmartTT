@extends('layouts.app')
@section('title')
    Create User - {{config('app.name')}}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('users.index')}}">{{__('Users')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Create')}}</li>
        </ol>
    </nav>

    <form role="form" action="{{route('users.store')}}" method="POST">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{__('Create User')}}</h3>
            </div>
            @csrf
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="name">{{__('Name')}}</label>
                    <input type="text" name="name" class="form-control" id="name"
                           value="{{old('name')}}" placeholder="Enter User Name">
                    @error('name')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">{{__('Email')}}</label>
                    <input type="email" name="email" class="form-control" id="email"
                           value="{{old('email')}}" placeholder="{{__('Enter Email')}}">
                    @error('email')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">{{__('Password')}}</label>
                    <input type="password" name="password" class="form-control" id="password"
                           value="{{old('password')}}" placeholder="{{__('Enter Password')}}">
                    @error('password')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password_confirmation">{{__('Confirm your password')}}</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           id="password_confirmation"
                           value="{{old('password_confirmation')}}" placeholder="{{__('Enter Confirm your password')}}">
                    @error('password_confirmation')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
            </div>
        </div>
    </form>
@endsection
