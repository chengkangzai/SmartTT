@extends('smartTT.layout.master')
@section('title')
    Trip Management - {{config('app.name')}}
@endsection
@section('cdn')
    <link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.css')}}">
@endsection

@section('content')
    <section class="content-header">
        <h1><b>Trip Management</b></h1>
        <ol class="breadcrumb">
            <li class="active"><a href="{{route('trip.index')}}"><i class="fa fa-dashboard"></i> Trip </a></li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Trip Management</h3>
                <div class="m-2 btn-group-vertical pull-right">
                    <a href="{{route('trip.create')}}" class="btn btn-success">Create</a>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="indexTable" class="table table-bordered table-hover ">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Trip Fee</th>
                            <th>Trip Capacity</th>
                            <th>Trip Departure</th>
                            <th>Tour</th>
                            <th>Airline</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trips as $trip)
                            <tr>
                                <td>{{$trip->id}}</td>
                                <td>RM{{number_format($trip->fee/100,2)}}</td>
                                <td>{{$trip->capacity}}</td>
                                <td>{{$trip->depart_time->format(config('app.date_format'))}}</td>
                                <td>{{$trip->tour->name}}</td>
                                <td>{{$trip->airline}}</td>
                                <td>
                                    <a href="{{route('trip.show',['trip'=>$trip->id])}}" class="btn btn-info">Show</a>
                                    <a href="{{route('trip.edit',['trip'=>$trip->id])}}"
                                       class="btn btn-primary">Edit</a>
                                    <form action="{{route('trip.destroy',['trip'=>$trip->id])}}" style="display: inline"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input class="btn btn-danger" type="submit" value="Delete"/>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-footer">
                {{$trips->links()}}
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script>
        $('#indexTable').DataTable({
            bInfo: false,
            paging: false,
        });
    </script>
@endsection
