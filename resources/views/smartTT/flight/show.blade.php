@extends('smartTT.layout.master')
@section('title')
    Flight Management - {{config('app.name')}}
@endsection
@section('content')
    <section class="content-header">
        <h1><b>Flight </b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('flight.index')}}"><i class="fa fa-dashboard"></i> Flight </a></li>
            <li class="active">Show</li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Flight Information</h3>
                <div class="pull-right">
                    <a href="{{route('flight.edit',['flight'=>$flight->id])}}" class="btn btn-primary">Edit</a>
                    <form action="{{route('flight.destroy',['flight'=>$flight->id])}}" method="POST"
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
                            <th>Depart Time</th>
                            <th>Arrival Time</th>
                            <th>Fee (Rm)</th>
                            <th>Airline</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{$flight->id}}</td>
                            <td>{{$flight->depart_time}}</td>
                            <td>{{$flight->arrive_time}}</td>
                            <td>RM {{number_format($flight->fee/100,2)}}</td>
                            <td>{{$flight->airline->name}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
