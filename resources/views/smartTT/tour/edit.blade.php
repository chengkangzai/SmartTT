@extends('smartTT.layout.master')
@section('title')
    Edit Tour - {{config('app.name')}}
@endsection
@section('content')
    <section class="content-header">
        <h1><b>Edit Tour : {{$tour->name}} </b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('tour.index')}}"><i class="fa fa-dashboard"></i> Tour</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <section class="content container-fluid w-75">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Tour</h3>
            </div>
            <form role="form" action="{{route('tour.update',['tour'=>$tour->id])}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="box-body">
                    <div class="form-group @error('name') has-error @enderror">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control " id="name"
                               value="{{$tour->name}}" placeholder="Enter Tour Name">
                        @error('name')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('tour_code') has-error @enderror">
                        <label for="name">Tour Code</label>
                        <input type="text" name="tour_code" class="form-control " id="tour_code"
                               value="{{$tour->tour_code}}" placeholder="Enter Tour Code">
                        @error('tour_code')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('destination') has-error @enderror">
                        <label for="name">Destination</label>
                        <input type="text" name="destination" class="form-control " id="destination"
                               value="{{$tour->destination}}" placeholder="Enter Destination">
                        @error('destination')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('category') has-error @enderror">
                        <label for="name">Category</label>
                        <input type="text" name="category" class="form-control " id="category"
                               value="{{$tour->category}}" placeholder="Enter Category">
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
