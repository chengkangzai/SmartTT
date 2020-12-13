@extends('smartTT.layout.master')
@section('title')
    Edit Flight - {{config('app.name')}}
@endsection
@section('content')
    <section class="content-header">
        <h1><b>Edit Flight : {{$flight->name}} </b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('flight.index')}}"><i class="fa fa-dashboard"></i> Flight</a></li>
            <li><a href="{{route('flight.show',['flight'=>$flight->id])}}">{{$flight->name}}</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <section class="content container-fluid w-75">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Flight</h3>
            </div>
            <form role="form" action="{{route('flight.update',['flight'=>$flight->id])}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="box-body">
                    <div class="form-group @error('name') has-error @enderror">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control " id="name"
                               value="{{$flight->name}}" placeholder="Enter Flight Name">
                        @error('name')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('flight_code') has-error @enderror">
                        <label for="name">Flight Code</label>
                        <input type="text" name="flight_code" class="form-control " id="flight_code"
                               value="{{$flight->flight_code}}" placeholder="Enter Flight Code">
                        @error('flight_code')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('destination') has-error @enderror">
                        <label for="name">Destination</label>
                        <input type="text" name="destination" class="form-control " id="destination"
                               value="{{$flight->destination}}" placeholder="Enter Destination">
                        @error('destination')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('category') has-error @enderror">
                        <label for="name">Category</label>
                        <input type="text" name="category" class="form-control " id="category"
                               value="{{$flight->category}}" placeholder="Enter Category">
                        @error('category')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('itinerary') has-error @enderror">
                        <label for="itinerary">Itinerary</label>
                        <input type="file" id="itinerary" name="itinerary" accept='application/pdf'>
                        @error('itinerary')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('thumbnail') has-error @enderror">
                        <label for="thumbnail">Thumbnail</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept='image/*'>
                        @error('thumbnail')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </section>
@endsection
