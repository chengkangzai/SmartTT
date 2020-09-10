@extends('smartTT.layout.master')
@section('title')
    Create Tour Description - {{config('app.name')}}
@endsection

@section('content')
    <section class="content-header">
        <h1><b>Tour Description</b></h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-dashboard"></i> Tour Description</li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <section class="content container-fluid w-75">
        <form role="form" action="{{route('tourDescription.update',['tourDescription'=>$tourDescription->id])}}"
              method="POST" >
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tour Description</h3>
                </div>
                <div class="box-body">
                    <div class="form-group @error('place') has-error @enderror">
                        @csrf
                        @method('PUT')
                        <label for="place">Place </label>
                        <input type="text" name="place" class="form-control" id="place"
                               value="{{$tourDescription->place}}" placeholder="Enter the main visit place 1">
                        @error('place')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('des') has-error @enderror">
                        <label for="des.">Description </label>
                        <textarea type="text" name="des" class="form-control" id="des" rows="5"
                                  placeholder="Enter Description 1">{{$tourDescription->description}}</textarea>
                        @error('des')
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
