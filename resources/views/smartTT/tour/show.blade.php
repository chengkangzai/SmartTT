@extends('smartTT.layout.master')
@section('title')
    Tour Management - {{config('app.name')}}
@endsection
@section('content')
    <section class="content-header">
        <h1><b>Tour : {{$tour->name}} </b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('tour.index')}}"><i class="fa fa-dashboard"></i> Tour </a></li>
            <li class="active">Show</li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-2">
                <img src="{{$thumbnailUrl}}" alt="" class="img-responsive img-thumbnail image">
            </div>
            <div class="col-lg-10">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Tour Information</h3>
                        <div class="pull-right">
                            <a href="{{route('tour.edit',['tour'=>$tour->id])}}" class="btn btn-primary">Edit</a>
                            <form action="{{route('tour.destroy',['tour'=>$tour->id])}}" method="POST"
                                  style="display: inline">
                                @method('DELETE')
                                @csrf
                                <input class="btn btn-danger" type="submit" value="Delete"/>
                            </form>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tour Name</th>
                                <th>Tour Code</th>
                                <th>Destination</th>
                                <th>Category</th>
                                <th>Itinerary</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{$tour->id}}</td>
                                <td>{{$tour->name}}</td>
                                <td>{{$tour->tour_code}}</td>
                                <td>{{$tour->destination}}</td>
                                <td>{{$tour->category}}</td>
                                <td>
                                    <a href="{{$itineraryUrl}}" class="btn btn-info">View</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Tour Description</h3>
                        <div class="pull-right">
                            <a href="{{route('tour.edit',['tour'=>$tour->id])}}" class="btn btn-primary">Edit</a>
                            <form action="{{route('tour.destroy',['tour'=>$tour->id])}}" method="POST"
                                  style="display: inline">
                                @method('DELETE')
                                @csrf
                                <input class="btn btn-danger" type="submit" value="Delete"/>
                            </form>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tour Name</th>
                                <th>Tour Code</th>
                                <th>Destination</th>
                                <th>Category</th>
                                <th>Itinerary</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{$tour->id}}</td>
                                <td>{{$tour->name}}</td>
                                <td>{{$tour->tour_code}}</td>
                                <td>{{$tour->destination}}</td>
                                <td>{{$tour->category}}</td>
                                <td>
                                    <a href="{{$itineraryUrl}}" class="btn btn-info">View</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
        {{--TODO--}}
        {{--1. link trips--}}
        {{--2. link Tour description--}}
            </div>
        </div>
    </section>
@endsection
