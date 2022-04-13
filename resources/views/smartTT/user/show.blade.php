@extends('layouts.app')
@section('title')
    User Management - {{config('app.name')}}
@endsection
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('users.index')}}">{{__('Users')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Show')}}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{__('User Information')}}</h3>
            <div class="pull-right">
                <a href="{{route('users.edit',$user)}}" class="btn btn-primary">{{__('Edit')}}</a>
                <form action="{{route('users.sendResetPassword',$user)}}" method="POST"
                      style="display: inline">
                    @method('DELETE') @csrf
                    <input class="btn btn-info" type="submit" value="{{__('Send Password Reset Email')}}"/>
                </form>
                @can('Delete User')
                    <form action="{{route('users.destroy',$user)}}" method="POST"
                          style="display: inline">
                        @method('DELETE') @csrf
                        <input class="btn btn-danger" type="submit" value="{{__('Delete')}}"/>
                    </form>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th>{{__('ID')}}</th>
                    <th>{{__('User Name')}}</th>
                    <th>{{__('User Email')}}</th>
                    <th>{{__('User Role')}}</th>
                    <th>{{__('User Joined At')}}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        <a href="{{route('roles.show',$user->roles->first())}}"
                           class="btn btn-primary">{{$user->roles->first()->name}}</a>
                    </td>
                    <td>{{$user->created_at}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
