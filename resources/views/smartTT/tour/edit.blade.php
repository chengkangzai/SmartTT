@extends('layouts.app')
@section('title')
    Edit Tour - {{config('app.name')}}
@endsection
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('tours.index')}}">{{__('Tour')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Edit')}}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Tour</h3>
        </div>
        <form role="form" action="{{route('tours.update',['tour'=>$tour->id])}}" method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name"
                           value="{{$tour->name}}" placeholder="Enter Tour Name">
                    @error('name')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tour_code" class="form-label">Tour Code</label>
                    <input type="text" name="tour_code" class="form-control" id="tour_code"
                           value="{{$tour->tour_code}}" placeholder="Enter Tour Code">
                    @error('tour_code')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="destination" class="form-label">Destination</label>
                    <input type="text" name="destination" class="form-control" id="destination"
                           value="{{$tour->destination}}" placeholder="Enter Destination">
                    @error('destination')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" id="category"
                           value="{{$tour->category}}" placeholder="Enter Category">
                    @error('category')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="itinerary" class="form-label">Itinerary</label>
                    <input type="file" id="itinerary" name="itinerary" accept='application/pdf' class="form-control">
                    @error('itinerary')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="thumbnail" class="form-label">Thumbnail</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept='image/*' class="form-control">
                    @error('thumbnail')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection
