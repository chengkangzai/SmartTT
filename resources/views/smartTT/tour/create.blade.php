@extends('smartTT.layout.master')
@section('title')
    Create Tour - {{config('app.name')}}
@endsection

@section('content')
    <section class="content-header">
        <h1><b>Create Tour</b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('tour.index')}}"><i class="fa fa-dashboard"></i> Tour</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <section class="content container-fluid w-75">
        <form role="form" action="{{route('tour.store')}}" method="POST" enctype="multipart/form-data">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Create Tour</h3>
                </div>
                @csrf
                <div class="box-body">
                    <div class="form-group @error('name') has-error @enderror">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name"
                               value="{{old('name')}}" placeholder="Enter Tour Name">
                        @error('name')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('tour_code') has-error @enderror">
                        <label for="name">Tour Code</label>
                        <input type="text" name="tour_code" class="form-control" id="tour_code"
                               value="{{old('tour_code')}}" placeholder="Enter Tour Code">
                        @error('tour_code')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('destination') has-error @enderror">
                        <label for="name">Destination</label>
                        <input type="text" name="destination" class="form-control" id="destination"
                               value="{{old('destination')}}" placeholder="Enter Destination">
                        @error('destination')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('category') has-error @enderror">
                        <label for="name">Category</label>
                        <input type="text" name="category" class="form-control" id="category"
                               value="{{old('category')}}" placeholder="Enter Category">
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


            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tour Description</h3>
                </div>
                <div class="box-body">
                    <div class="form-group @error('place.0') has-error @enderror">
                        <label for="place.1">Place 1</label>
                        <input type="text" name="place[]" class="form-control" id="place.1"
                               value="{{old('place.0')}}" placeholder="Enter the main visit place 1">
                        @error('place.0')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('des.0') has-error @enderror">
                        <label for="des.1">Description 1</label>
                        <textarea type="text" name="des[]" class="form-control" id="des.1" rows="5"
                                  placeholder="Enter Description 1">{{old('des.0')}}</textarea>
                        @error('des.0')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('place.1') has-error @enderror">
                        <label for="place.2">Place 2</label>
                        <input type="text" name="place[]" class="form-control" id="place.2"
                               value="{{old('place.1')}}" placeholder="Enter the main visit place 2">
                        @error('place.1')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('des.1') has-error @enderror">
                        <label for="des.2">Description 2</label>
                        <textarea type="text" name="des[]" class="form-control" id="des.2" rows="5"
                                  placeholder="Enter Description 2">{{old('des.1')}}</textarea>
                        @error('des.1')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('place.2') has-error @enderror">
                        <label for="place.3">Place 3</label>
                        <input type="text" name="place[]" class="form-control" id="place.3"
                               value="{{old('place.2')}}" placeholder="Enter the main visit place 3">
                        @error('place.2')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('des.2') has-error @enderror">
                        <label for="des.3">Description 3</label>
                        <textarea type="text" name="des[]" class="form-control" id="des.3" rows="5"
                                  placeholder="Enter Description 3">{{old('des.2')}}</textarea>
                        @error('des.2')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </section>
@endsection
