@extends('layouts.app')
@section('title')
    Create Flight - {{config('app.name')}}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('flights.index')}}">{{__('Flights')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Create')}}</li>
        </ol>
    </nav>

    <form role="form" action="{{route('flights.store')}}" method="POST" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header with-border">
                <h3 class="card-title">{{__('Create Flight')}}</h3>
            </div>
            @csrf
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label" for="depart_time">{{__('Depart Time')}}</label>
                    <input type='datetime-local' class="form-control" name="depart_time" id="depart_time"
                           value="{{old('depart_time','')}}"/>
                    @error('depart_time')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="arrive_time">{{__('Arrive Time')}}</label>
                    <input type='datetime-local' class="form-control" name="arrive_time" id="arrive_time"
                           value="{{old('arrive_time')}}"/>
                    @error('arrive_time')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="mb-3">
                    <label class="form-label" for="fee">{{__('Fee')}}</label>
                    <input type="number" name="fee" class="form-control" id="fee"
                           value="{{old('fee',0)}}" step="1" placeholder="{{__('Enter Flight Fee')}}">
                    @error('fee')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="airline_id">{{__('Flight')}}</label>
                    <select name="airline_id" class="form-control select2 " id="airline_id" required>
                        <option value="0" disabled selected>{{__('Please Select')}}</option>
                        @foreach($airlines as $airline)
                            <option value="{{$airline->id}}"> {{$airline->name}}</option>
                        @endforeach
                    </select>

                    @error('airline_id')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="depart_airport">{{__('Depart Airport')}}</label>
                    <select name="depart_airport" class="form-control select2 " id="depart_airport" required>
                        <option value="0" disabled selected>{{__('Please Select')}}</option>
                        @foreach($airports as $airport)
                            <option value="{{$airport->id}}"> {{$airport->name}}</option>
                        @endforeach
                    </select>
                    @error('depart_airport')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="arrival_airport">{{__('Arrival Airport')}}</label>
                    <select name="arrival_airport" class="form-control select2 " id="arrival_airport" required>
                        <option value="0" disabled selected>{{__('Please Select')}}</option>
                        @foreach($airports as $airport)
                            <option value="{{$airport->id}}"> {{$airport->name}}</option>
                        @endforeach
                    </select>

                    @error('arrival_airport')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="flight_class">{{__('Flight Class')}}</label>
                    <select name="flight_class" class="form-control select2" id="flight_class" required>
                        <option value="0" disabled selected>{{__('Please Select')}}</option>
                        @foreach(\App\Models\Flight::FCLASS as $key=>$class)
                            <option value="{{$key}}"> {{$class}} </option>
                        @endforeach
                    </select>

                    @error('flight_class')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="flight_type">{{__('Flight Class')}}</label>
                    <select name="flight_type" class="form-control select2" id="flight_type" required>
                        <option value="0" disabled selected>{{__('Please Select')}}</option>
                        @foreach(\App\Models\Flight::TYPE as $key=>$type)
                            <option value="{{$key}}"> {{$type}} </option>
                        @endforeach
                    </select>
                    @error('flight_type')
                    <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        $('#airline_id').select2();
        $('#depart_airport').select2();
        $('#arrival_airport').select2();
        $('#flight_class').select2();
        $('#flight_type').select2();
    </script>
@endsection
