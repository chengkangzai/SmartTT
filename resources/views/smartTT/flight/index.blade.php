@extends('smartTT.layout.master')
@section('title')
    Flight Management - {{config('app.name')}}
@endsection

@section('content')
    <section class="content-header">
        <h1><b>Flight Management</b></h1>
        <ol class="breadcrumb">
            <li class="active"><a href="{{route('flight.index')}}"><i class="fa fa-dashboard"></i> Flight </a></li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Flight Management</h3>
                <div class="m-2 btn-group-vertical pull-right">
                    <a href="{{route('flight.create')}}" class="btn btn-success">Create</a>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">

                    <table id="indexTable" class="table table-bordered table-hover ">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Depart Time</th>
                            <th>Arrival Time</th>
                            <th>Fee (Rm)</th>
                            <th>Airline</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($flights as $flight)
                            <tr>
                                <td>{{$flight->id}}</td>
                                <td>{{$flight->depart_time->format(config('app.date_format'))}}</td>
                                <td>{{$flight->arrive_time->format(config('app.date_format'))}}</td>
                                <td>RM {{number_format($flight->fee/100,2)}}</td>
                                <td>{{$flight->airline->name}}</td>
                                <td>
                                    <a href="{{route('flight.show',['flight'=>$flight->id])}}"
                                       class="btn btn-info">Show</a>
                                    <a href="{{route('flight.edit',['flight'=>$flight->id])}}"
                                       class="btn btn-primary">Edit</a>
                                    <form action="{{route('flight.destroy',['flight'=>$flight->id])}}"
                                          style="display: inline"
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
                    <div class="box-footer">
                        {{$flights->links()}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $('#indexTable').DataTable({
            bInfo: false,
            paging: false,
        });
    </script>
@endsection
