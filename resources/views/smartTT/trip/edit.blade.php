@extends('smartTT.layout.master')
@section('title')
    Edit Trip - {{config('app.name')}}
@endsection
@section('content')
    <section class="content-header">
        <h1><b>Edit Trip : {{$trip->tour->name}} </b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('trip.index')}}"><i class="fa fa-dashboard"></i> Trip</a></li>
            <li><a href="{{route('trip.show',['trip'=>$trip->id])}}">{{$trip->tour->name}}</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <section class="content container-fluid w-75">
        <div class="box box-primary">
            <form role="form" action="{{route('trip.update',['trip'=>$trip->id])}}" method="POST"
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Trip</h3>
                </div>
                @csrf
                <div class="box-body">
                    <div class="form-group @error('fee') has-error @enderror">
                        <label for="fee">Fee (RM)</label>
                        <input type="number" name="fee" class="form-control" id="fee"
                               value="{{old('fee',$trip->fee/100)}}" placeholder="Enter Trip Fee">
                        @error('fee')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group @error('capacity') has-error @enderror">
                        <label for="capacity">Capacity</label>
                        <input type="number" name="capacity" class="form-control" id="capacity"
                               value="{{old('capacity',$trip->capacity)}}" placeholder="Enter Capacity of this trip">
                        @error('capacity')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group @error('tour') has-error @enderror">
                        <label for="tour">Tour</label>
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

                    <div class="form-group @error('depart_time') has-error @enderror">
                        <label for="depart_time">Depart Time</label>
                        <div class='input-group date' id='depart_time'>
                            <input type='text' class="form-control" name="depart_time"
                                   value="{{old('depart_time',$trip->depart_time)}}"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        @error('depart_time')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <hr>
                    <div class="form-group @error('airline') has-error @enderror">
                        <label for="airline">Airline</label>
                        <select name="airline" class="form-control select2 " id="airlineSelect">
                            <option value="{{$trip->airline}}" selected>{{$trip->airline}}</option>
                            <option disabled>-----------------</option>
                            @foreach($airlines as $airline)
                                <option value="{{$airline->name}}"> {{$airline->name}} </option>
                            @endforeach
                        </select>

                        @error('airline')
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
        </div>
    </section>
@endsection

@section('script')
    <script>
        $("#airlineSelect").select2({});
        $('#depart_time').datetimepicker({
            date: new Date("{{$trip->depart_time}}")
        });
    </script>
@endsection
