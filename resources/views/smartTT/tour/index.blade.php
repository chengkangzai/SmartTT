@extends('smartTT.layout.master')
@section('title')
    Tour Management - {{config('app.name')}}
@endsection

@section('content')
    <section class="content-header">
        <h1><b>Tour Management</b></h1>
        <ol class="breadcrumb">
            <li class="active"><a href="{{route('tour.index')}}"><i class="fa fa-dashboard"></i> Tour </a></li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tour Management</h3>
                <div class="m-2 btn-group-vertical pull-right">
                    <a href="{{route('tour.create')}}" class="btn btn-success">Create</a>
                </div>
            </div>
            <div class="box-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="indexTable" class="table table-bordered table-hover ">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tour Name</th>
                            <th>Tour Code</th>
                            <th>Destination</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tours as $tour)
                            <tr>
                                <td>{{$tour->id}}</td>
                                <td>{{$tour->name}}</td>
                                <td>{{$tour->tour_code}}</td>
                                <td>{{$tour->destination}}</td>
                                <td>{{$tour->category}}</td>
                                <td>
                                    <a href="{{route('tour.show',['tour'=>$tour->id])}}" class="btn btn-info">Show</a>
                                    <a href="{{route('tour.edit',['tour'=>$tour->id])}}"
                                       class="btn btn-primary">Edit</a>
                                    <form action="{{route('tour.destroy',['tour'=>$tour->id])}}" style="display: inline"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input class="btn btn-danger" type="submit" value="Delete"/>
                                    </form>
                                </td>
                            </tr>
                            {{--                        FORELSE--}}
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer">
                        {{$tours->links()}}
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
