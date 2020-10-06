@extends('smartTT.layout.master')
@section('title')
    Trip Management - {{config('app.name')}}
@endsection
@section('content')
    <section class="content-header">
        <h1><b>Trip : {{$trip->tour->name}} </b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('trip.index')}}"><i class="fa fa-dashboard"></i> Trip </a></li>
            <li class="active">Show</li>
        </ol>
    </section>

    <section class="content container-fluid">

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Trip Information</h3>
                    <div class="pull-right">
                        <a href="{{route('trip.edit',['trip'=>$trip->id])}}" class="btn btn-primary">Edit</a>
                        <form action="{{route('trip.destroy',['trip'=>$trip->id])}}" method="POST"
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
                                <th>Trip Fee</th>
                                <th>Trip Departure</th>
                                <th>Trip Capacity</th>
                                <th>Tour</th>
                                <th>Airline</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{$trip->id}}</td>
                                <td>RM {{number_format($trip->fee/100,2)}}</td>
                                <td>{{$trip->depart_time}}</td>
                                <td>{{$trip->capacity}}</td>
                                <td>{{$trip->tour->name}}</td>
                                <td>{{$trip->airline}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
{{--TODO LIST OF user who booked this trip--}}
    </section>
@endsection





