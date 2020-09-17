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
            <div class="col-lg-10 ">
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
                        <div class="table-responsive">
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
                </div>


                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Tour Description</h3>
                        <div class="pull-right">
                            <div class="pull-right">
                                <a href="#" class="btn btn-success" data-toggle="modal"
                                   data-target="#addTourDescriptionModal">Add</a>

                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="" >
                        @forelse($tourDes as $des)
                            <div class="card col-lg-4 col-md-12 " style="height: 200px;padding-bottom: 10px">
                                <div class="card-body">
                                    <h3 class="card-title">{{$des->place}}</h3>
                                    <p class="card-text text-truncate">{{$des->description}}</p>
                                </div>
                                <div class="mx-auto ">
                                    <form class="form" style="display: inline;" method="POST"
                                          action="{{route('tourDescription.destroy',['tourDescription'=>$des->id])}}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="submit" class="btn btn-danger" value="Delete">
                                    </form>
                                    <a href="{{route('tourDescription.edit',['tourDescription'=>$des->id])}}"
                                       class="btn btn-info">Edit</a>
                                </div>

                            </div>
                        @empty
                            <h4 class="text-center">Nothing to show</h4>
                        @endforelse
                        </div>
                    </div>
                </div>

                {{--TODO--}}
                {{--1. link trips--}}
                {{--2. link Tour description--}}
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <div class="modal fade" id="addTourDescriptionModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Tour Description to Tour:{{$tour->name}}</h4>
                </div>
                <div class="modal-body">
                    <form action="{{route('tourDescription.attach',['tour'=>$tour->id])}}" method="POST"
                          id="addTourDescription">
                        @csrf
                        @method('POST')
                        <div class="form-group">
                            <label for="place">Description Place</label>
                            <input class="form-control select2" id="place" name="place"
                                   placeholder="Enter the name of the Place"/>
                        </div>
                        <div class="form-group">
                            <label for="des"> Place Description </label>
                            <textarea name="des" id="des" class="form-control" rows="5"
                                      placeholder="Enter the description for the place above"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="form-control btn btn-success" value="Submit">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection


