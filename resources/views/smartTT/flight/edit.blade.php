@extends('smartTT.layout.master')
@section('title')
    Edit Flight - {{config('app.name')}}
@endsection
@section('content')
    <section class="content-header">
        <h1><b>Edit Flight</b></h1>
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
            <form role="form" action="{{route('flight.update',['flight'=>$flight->id])}}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="box-body">
                    <div class="form-group @error('depart_time') has-error @enderror">
                        <label for="depart_time">Depart Time</label>
                        <div class='input-group date' id='depart_time'>
                            <input type='text' class="form-control" name="depart_time"
                                   value="{{ old('depart_time',$flight->depart_time) }}">
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

                    <div class="form-group @error('arrive_time') has-error @enderror">
                        <label for="arrive_time">Arrive Time</label>
                        <div class='input-group date' id='arrive_time'>
                            <input type='text' class="form-control" name="arrive_time"
                                   value="{{old('arrive_time',$flight->arrive_time)}}"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        @error('arrive_time')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group @error('fee') has-error @enderror">
                        <label for="name">Fee</label>
                        <input type="number" name="fee" class="form-control" id="fee"
                               value="{{old('fee', $flight->fee/100 )}}" step="1"
                               placeholder="Enter Flight Fee">
                        @error('fee')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group @error('airline_id') has-error @enderror">
                        <label for="airline_id">Flight</label>
                        <select name="airline_id" class="form-control select2 " id="airline_id" required>
                            <option value="0" disabled selected> Please Select</option>
                            @foreach($airlines as $airline)
                                <option value="{{$airline->id}}"
                                    {{$flight->airline->id === $airline->id ? 'selected' :'' }}> {{$airline->name}}</option>
                            @endforeach
                        </select>

                        @error('airline_id')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group @error('depart_airport') has-error @enderror">
                        <label for="depart_airport">Depart Airport</label>
                        <select name="depart_airport" class="form-control select2 " id="depart_airport" required>
                            <option value="0" disabled selected> Please Select</option>
                            @foreach($airports as $airport)
                                <option value="{{$airport->id}}"
                                    {{$flight->depart_airports->id === $airport->id ? 'selected' :'' }}
                                > {{$airport->name}}</option>
                            @endforeach
                        </select>

                        @error('depart_airport')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group @error('arrival_airport') has-error @enderror">
                        <label for="arrival_airport">Arrival Airport</label>
                        <select name="arrival_airport" class="form-control select2 " id="arrival_airport" required>
                            <option value="0" disabled selected> Please Select</option>
                            @foreach($airports as $airport)
                                <option value="{{$airport->id}}"
                                    {{$flight->arrive_airport->id === $airport->id ? 'selected' :'' }}
                                > {{$airport->name}}</option>
                            @endforeach
                        </select>

                        @error('arrival_airport')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group @error('flight_class') has-error @enderror">
                        <label for="flight_class">Flight Class</label>
                        <select name="flight_class" class="form-control select2 " id="flight_class" required>
                            <option value="0" disabled selected> Please Select</option>
                            @foreach(\App\Models\Flight::FCLASS as $key=>$class)
                                <option value="{{$key}}"
                                    {{$flight->flight_class === $class ? 'selected' :'' }}
                                > {{$class}} </option>
                            @endforeach
                        </select>

                        @error('flight_class')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group @error('flight_type') has-error @enderror">
                        <label for="flight_type">Flight Class</label>
                        <select name="flight_type" class="form-control select2 " id="flight_type" required>
                            <option value="0" disabled selected> Please Select</option>
                            @foreach(\App\Models\Flight::TYPE as $key=>$type)
                                <option value="{{$key}}"
                                    {{$flight->flight_type === $type ? 'selected' :'' }}
                                > {{$type}} </option>
                            @endforeach
                        </select>

                        @error('flight_type')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $('#depart_time').datetimepicker({{$flight->depart_time}});
        $('#arrive_time').datetimepicker();
        $('#airline_id').select2();
        $('#depart_airport').select2();
        $('#arrival_airport').select2();
        $('#flight_class').select2();
        $('#flight_type').select2();
    </script>
@endsection
