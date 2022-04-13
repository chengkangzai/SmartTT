@extends('layouts.app')
@section('title')
    Create Tour - {{config('app.name')}}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('tours.index')}}">{{__('Tour')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Create')}}</li>
        </ol>
    </nav>

    <form role="form" action="{{route('tours.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header with-border">
                <h3 class="card-title">{{__('Create Tour')}}</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">{{__('Name')}}</label>
                    <input type="text" name="name" class="form-control" id="name"
                           value="{{old('name')}}" placeholder="Enter Tour Name">
                    @error('name')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tour_code" class="form-label">{{__('Tour Code')}}</label>
                    <input type="text" name="tour_code" class="form-control" id="tour_code"
                           value="{{old('tour_code')}}" placeholder="Enter Tour Code">
                    @error('tour_code')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="destination" class="form-label">{{__('Destination')}}</label>
                    <input type="text" name="destination" class="form-control" id="destination"
                           value="{{old('destination')}}" placeholder="Enter Destination">
                    @error('destination')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">{{__('Category')}}</label>
                    <input type="text" name="category" class="form-control" id="category"
                           value="{{old('category')}}" placeholder="Enter Category">
                    @error('category')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="itinerary" class="form-label">{{__('Itinerary')}}</label>
                    <input type="file" id="itinerary" name="itinerary" accept='application/pdf' class="form-control">
                    @error('itinerary')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="thumbnail" class="form-label">{{__('Thumbnail')}}</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept='image/*' class="form-control">
                    @error('thumbnail')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card mt-2">
            <div class="card-header with-border">
                <h3 class="card-title">Tour Description</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="place.1" class="form-label">Place 1</label>
                    <input type="text" name="place[]" class="form-control" id="place.1"
                           value="{{old('place.0')}}" placeholder="Enter the main visit place 1">
                    @error('place.0')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="des.1" class="form-label">Description 1</label>
                    <textarea type="text" name="des[]" class="form-control" id="des.1" rows="5"
                              placeholder="Enter Description 1">{{old('des.0')}}</textarea>
                    @error('des.0')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="place.2" class="form-label">Place 2</label>
                    <input type="text" name="place[]" class="form-control" id="place.2"
                           value="{{old('place.1')}}" placeholder="Enter the main visit place 2">
                    @error('place.1')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="des.2" class="form-label">Description 2</label>
                    <textarea type="text" name="des[]" class="form-control" id="des.2" rows="5"
                              placeholder="Enter Description 2">{{old('des.1')}}</textarea>
                    @error('des.1')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="place.3" class="form-label">Place 3</label>
                    <input type="text" name="place[]" class="form-control" id="place.3"
                           value="{{old('place.2')}}" placeholder="Enter the main visit place 3">
                    @error('place.2')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="des.3" class="form-label">Description 3</label>
                    <textarea type="text" name="des[]" class="form-control" id="des.3" rows="5"
                              placeholder="Enter Description 3">{{old('des.2')}}</textarea>
                    @error('des.2')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
