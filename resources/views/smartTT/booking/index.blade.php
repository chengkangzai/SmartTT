@extends('smartTT.layout.master')
@section('title')
    Booking Management - {{config('app.name')}}
@endsection

@section('content')
    <section class="content-header">
        <h1><b>Booking Management</b></h1>
        <ol class="breadcrumb">
            <li class="active"><a href="{{route('booking.index')}}"><i class="fa fa-dashboard"></i> Booking </a></li>
        </ol>
    </section>

    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Booking Management</h3>
                <div class="m-2 btn-group-vertical pull-right">
                    <a href="{{route('booking.create')}}" class="btn btn-success">Create</a>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">

                    <table id="indexTable" class="table table-bordered table-hover ">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Trip</th>
                            <th>Adult</th>
                            <th>Child</th>
                            <th>Customer</th>
                            <th>Discount</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>{{$booking->id}}</td>
                                <td>
                                    <a href="{{route('trip.show',['trip'=>$booking->trips->id])}}"
                                       class="btn btn-sm btn-primary">{{$booking->trips->tour->name}}</a>
                                </td>
                                <td>{{$booking->adult}}</td>
                                <td>{{$booking->child}}</td>
                                <td>{{$booking->users->name}}</td>
                                <td>RM {{number_format($booking->discount)}}</td>
                                <td>RM {{number_format($booking->total_fee)}}</td>
                                <td>
                                    <a href="{{route('booking.show',['booking'=>$booking->id])}}"
                                       class="btn btn-info">Show</a>
                                    <a href="{{route('booking.edit',['booking'=>$booking->id])}}"
                                       class="btn btn-primary">Edit</a>
                                    <form action="{{route('booking.destroy',['booking'=>$booking->id])}}"
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
                        {{$bookings->links()}}
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
