@extends('layouts.app')
@section('title')
    Create Tour Description - {{config('app.name')}}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item"><a
                    href="{{route('tours.show',$tourDescription->tour()->first()->id)}}">{{__('Tour')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Edit')}}</li>
        </ol>
    </nav>


    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{__('Update')}}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{route('tourDescriptions.update',['tourDescription'=>$tourDescription->id])}}"
                  method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="place" class="form-label">Place </label>
                    <input type="text" name="place" class="form-control" id="place"
                           value="{{$tourDescription->place}}" placeholder="{{__('Enter the main visit place 1')}}">
                    @error('place')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="des" class="form-label">Description </label>
                    <textarea type="text" name="des" class="form-control" id="des" rows="5"
                              placeholder="{{__('Enter Description 1')}}">{{$tourDescription->description}}</textarea>
                    @error('des')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
@endsection
