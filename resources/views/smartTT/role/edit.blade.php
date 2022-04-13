@extends('layouts.app')

@section('title')
    Roles - {{ config('app.name') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('roles.index')}}">{{__('Roles')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Edit')}}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{__('Edit User Role')}}</h3>
        </div>
        <form role="form" action="{{route('roles.update',$role)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input type="text" name="name" class="form-control" id="name"
                           placeholder="{{__('Enter User Role Name')}}" value="{{$role->name}}">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
            </div>
        </form>
    </div>
@endsection
