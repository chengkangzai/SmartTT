@extends('smartTT.layout.master')
@section('title')
    Edit Booking - {{config('app.name')}}
@endsection
@section('content')
    <section class="content-header">
        <h1><b>Edit Booking</b></h1>
        <ol class="breadcrumb">
            <li><a href="{{route('booking.index')}}"><i class="fa fa-dashboard"></i> Booking</a></li>
            <li><a href="{{route('booking.show',['booking'=>$booking->id])}}">{{$booking->name}}</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <section class="content container-fluid w-75">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Booking</h3>
            </div>
            <form role="form" action="{{route('booking.update',['booking'=>$booking->id])}}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="box-body">

                    <div class="form-group @error('trip_id') has-error @enderror">
                        <label for="trip_id">Trips</label>
                        <select name="trip_id" class="form-control select2 " id="trip_id" required>
                            <option value="0" disabled selected> Please Select</option>
                            @foreach($trips as $key=>$trip)
                                <option value="{{$trip->id}}" data-price="{{$trip->price}}"
                                    {{$booking->trips->id === $key ?'selected' :''}} >
                                    {{$trip->tour->name}} ({{$trip->depart_time}}) (${{$trip->fee/100}})
                                </option>
                            @endforeach
                        </select>

                        @error('trip_id')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group @error('adult') has-error @enderror">
                        <label for="adult">Adult</label>
                        <input type="number" name="adult" class="form-control" id="adult" min="0"
                               value="{{old('adult',$booking->adult)}}" step="1" placeholder="Enter Total adult Number">
                        @error('adult')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group @error('child') has-error @enderror">
                        <label for="child">Child</label>
                        <small>Child is defined as children that is smaller than 12 years old</small>
                        <input type="number" name="child" class="form-control" id="child" min="0"
                               value="{{old('child',$booking->child)}}" step="1" placeholder="Enter Total Child Number">
                        @error('child')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group @error('user_id') has-error @enderror">
                        <label for="user_id">Customer</label>
                        <select name="user_id" class="form-control select2 " id="user_id" required>
                            <option value="0" disabled selected> Please Select</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}"
                                    {{$booking->users->id === $user->id ?'selected':''}}>
                                    {{$user->name}} ({{$user->email}})
                                </option>
                            @endforeach
                        </select>

                        @error('user_id')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>


                    <div class="form-group @error('discount') has-error @enderror">
                        <label for="discount">Discount</label>
                        <input type="number" name="discount" class="form-control" id="discount" min="0"
                               value="{{old('discount',$booking->discount)}}" step="1"
                               placeholder="Please enter Discount "/>
                        @error('discount')
                        <span class="help-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Total Price : <span id="fee">RM {{$booking->total_fee}}</span></label>
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
        $('#user_id').select2()

        let tripPrice = 0;

        const tripId = $('#trip_id');
        const child = $('#child');
        const adult = $('#adult');
        const discount = $('#discount');

        function updatePrice() {
            const adultVal = adult.val();
            const tripIdVal = tripId.val();
            if (adultVal === 0 || tripIdVal === null) {
                return;
            }
            $.ajax({
                type: "POST",
                url: "{{route('booking.calculatePrice')}}",
                data: {
                    tripId: tripId.val(),
                    child: child.val(),
                    adult: adult.val(),
                    discount: discount.val()
                },
                success: function (response) {
                    $('#fee').text('RM ' + response)
                }
            })
        }

        discount.on('change', updatePrice);
        child.on('change', updatePrice);
        adult.on('change', updatePrice);
        tripId.on('change', updatePrice)
    </script>
@endsection
