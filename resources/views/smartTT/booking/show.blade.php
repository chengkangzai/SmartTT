@extends('smartTT.layout.master')
@section('title')
    Booking Management - {{config('app.name')}}
@endsection
@section('content')
    <section class="content-header">
        <h1><b>Booking </b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('booking.index')}}"><i class="fa fa-dashboard"></i> Booking </a></li>
            <li class="active">Show</li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Booking Information</h3>
                <div class="pull-right">
                    <a href="{{route('booking.edit',['booking'=>$booking->id])}}" class="btn btn-primary">Edit</a>
                    <form action="{{route('booking.destroy',['booking'=>$booking->id])}}" method="POST"
                          style="display: inline">
                        @method('DELETE')
                        @csrf
                        <input class="btn btn-danger" type="submit" value="Delete"/>
                    </form>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Trip</th>
                            <th>Adult</th>
                            <th>Child</th>
                            <th>Customer</th>
                            <th>Discount</th>
                            <th>Total Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{$booking->id}}</td>
                            <td>{{$booking->trips->tour->name}}</td>
                            <td>{{$booking->adult}}</td>
                            <td>{{$booking->child}}</td>
                            <td>{{$booking->users->name}}</td>
                            <td>RM {{number_format($booking->discount)}}</td>
                            <td>RM {{number_format($booking->total_fee)}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
