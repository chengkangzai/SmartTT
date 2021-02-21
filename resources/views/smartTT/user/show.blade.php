@extends('smartTT.layout.master')
@section('title') User Management - {{config('app.name')}} @endsection
@section('content')
    <section class="content-header">
        <h1><b>User : {{$user->name}} </b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('user.index')}}"><i class="fa fa-dashboard"></i> User </a></li>
            <li class="active">Show</li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">User Information</h3>
                <div class="pull-right">
                    @if(auth()->user()->id == $user->id || auth()->user()->can('Edit User'))
                        <a href="{{route('user.edit',['user'=>$user->id])}}" class="btn btn-primary">Edit</a>
                        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#changePasswordModal">
                            Change Password</a> @endif @can('Delete User')
                        <form action="{{route('user.destroy',['user'=>$user->id])}}" method="POST"
                              style="display: inline">
                            @method('DELETE') @csrf
                            <input class="btn btn-danger" type="submit" value="Delete"/>
                        </form>
                    @endcan
                </div>
            </div>
            <div class="box-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>User Email</th>
                        <th>User Role</th>
                        <th>User Joined At</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            <a href="{{route('role.show',[$user->roles->first->id])}}"
                               class="btn btn-primary">{{$user->roles->first->name->name}}</a>
                        </td>
                        <td>{{$user->created_at}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
@endsection
@section('modal')
    <div class="modal fade" id="changePasswordModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Change your password</h4>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        <div class="form-group">
                            <label for="old_password">Current Password</label>
                            <input type="password" class="form-control" name="old_password" id="old_password"
                                   placeholder="Enter your Current Password"/>
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" name="new_password" id="new_password"
                                   placeholder="Enter your new password"/>
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirmation">New Password Confirmation</label>
                            <input type="password" class="form-control" name="new_password_confirmation"
                                   id="new_password_confirmation" placeholder="Confirm your new password"/>
                        </div>
                        <div class="form-group">
                            <input class="form-control btn btn-success" value="Submit" type="submit">
                        </div>

                        <div class="alert alert-danger hide" id="changePasswordModalAlert">
                            <ul id="changePasswordModalErrorList">

                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default pull-left" data-dismiss="modal"> Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('js/modal.js')}}"></script>
    <script>
        $("#changePasswordForm").on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{route('user.changePassword',['user'=>$user->id])}}",
                data: {
                    old_password: $("#old_password").val(),
                    new_password: $("#new_password").val(),
                    new_password_confirmation: $("#new_password_confirmation").val(),
                },
                success: function (response) {
                    $("#changePasswordModalAlert").removeClass('alert-danger').removeClass('hide').addClass('alert-success');
                    $("#changePasswordModalErrorList").html(`<ul id="changePasswordModalErrorList"></ul>`)
                        .append(`${response.message}`);

                    $("#changePasswordModal").modal('hide');
                    new Modal('Success !', ' Password is changed').show();
                },

                error: function (error) {
                    console.log(error.responseJSON.errors);
                    $("#changePasswordModalAlert").removeClass('hide');
                    $("#changePasswordModalErrorList").html(`<ul id="changePasswordModalErrorList"></ul>`)
                    for (const key in error.responseJSON.errors) {
                        if (error.responseJSON.errors.hasOwnProperty(key)) {
                            const element = error.responseJSON.errors[key];
                            element.forEach((value) => {
                                $("#changePasswordModalErrorList").append(`<li>${value}</li>`);
                            })

                        }
                    }
                }
            });
        });

        $('#changePasswordModal').on('hidden.bs.modal', function () {
            $("#changePasswordModalAlert").addClass('alert-danger').addClass('hide').removeClass('alert-success');
            $("#changePasswordModalErrorList").html(`<ul id="changePasswordModalErrorList"></ul>`)
            $("#old_password").val("");
            $("#new_password").val("");
            $("#new_password_confirmation").val("");
        })

    </script>
@endsection
