@extends('smartTT.layout.master')
@section('title')
    Create Flight - {{config('app.name')}}
@endsection

@section('content')
    <section class="content-header">
        <h1><b>Create Flight</b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('flight.index')}}"><i class="fa fa-dashboard"></i> Flight</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <section class="content container-fluid w-75">
        <form role="form" action="{{route('flight.store')}}" method="POST" enctype="multipart/form-data">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Create Flight</h3>
                </div>
                @csrf
                <div class="box-body">

                    <div class="form-group @error('depart_time') has-error @enderror">
                        <label for="depart_time">Depart Time</label>
                        <div class='input-group date' id='depart_time'>
                            <input type='text' class="form-control" name="depart_time"/>
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
                            <input type='text' class="form-control" name="arrive_time"/>
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
                               value="{{old('fee',0)}}" step="1" placeholder="Enter Flight Fee">
                        @error('fee')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group @error('airline_id') has-error @enderror">
                        <label for="airline_id">Flight</label>
                        <select name="airline_id" class="form-control select2 " id="airline_id">
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

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>


                </div>
            </div>
        </form>
    </section>
@endsection

@section('script')
    <script>
        $('#depart_time').datetimepicker();
        $('#arrive_time').datetimepicker();
        $('#airline_id').select2();
    </script>
@endsection
