@extends('layouts.app')
@section('title')
    Edit Trip - {{config('app.name')}}
@endsection
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('trips.index')}}">{{__('Trips')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Edit')}}</li>
        </ol>
    </nav>

    <section class="content container-fluid w-75">
        <div class="card">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">{{__('Edit Trip')}}</h3>
                </div>
                @csrf
                <div class="card-body">
                    <form role="form" action="{{route('trips.update',$trip)}}" method="POST">
                        <div class="mb-3">
                            <label for="fee" class="form-label">Fee (RM)</label>
                            <input type="number" name="fee" class="form-control" id="fee"
                                   value="{{old('fee',$trip->fee/100)}}" placeholder="Enter Trip Fee">
                            @error('fee')
                            <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="number" name="capacity" class="form-control" id="capacity"
                                   value="{{old('capacity',$trip->capacity)}}"
                                   placeholder="Enter Capacity of this trip">
                            @error('capacity')
                            <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tour" class="form-label">Tour</label>
                            <select name="tour" class="form-control">
                                <option selected value="{{$tour->id}}"> {{$tour->name}} </option>
                                @foreach($tours as $tour)
                                    <option value="{{$tour->id}}"> {{$tour->name}} </option>
                                @endforeach
                            </select>

                            @error('tour')
                            <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="depart_time" class="form-label">Depart Time</label>
                            <div class='input-group date' id='depart_time'>
                                <input type='datetime-local' class="form-control" name="depart_time"
                                       value="{{old('depart_time',$trip->depart_time)}}"/>
                            </div>
                            @error('depart_time')
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
        </div>
    </section>
@endsection
