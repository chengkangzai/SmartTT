@extends('layouts.app')
@section('title')
    Edit Booking - {{ config('app.name') }}
@endsection
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">{{ __('Bookings') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">{{ __('Edit Booking') }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('bookings.update', $booking) }}" method="POST" id="editForm">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label" for="trip_id">{{ __('Trips') }}</label>
                    <select name="trip_id" class="form-control select2 " id="trip_id" required>
                        <option value="0" disabled selected> {{ __('Please Select') }}</option>
                        @foreach ($trips as $key => $trip)
                            <option value="{{ $trip->id }}" data-price="{{ $trip->price }}"
                                {{ $booking->trips->id === $key ? 'selected' : '' }}>
                                {{ $trip->tour->name }} ({{ $trip->depart_time }}) (${{ $trip->fee / 100 }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="adult">{{ __('Adult') }}</label>
                    <input type="number" name="adult" class="form-control" id="adult" min="0"
                           value="{{ old('adult', $booking->adult) }}" step="1"
                           placeholder="{{ __('Enter Total adult Number') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="child">{{ __('Child') }}</label>
                    <small>{{ __('Child is defined as children that is smaller than 12 years old') }}</small>
                    <input type="number" name="child" class="form-control" id="child" min="0"
                           value="{{ old('child', $booking->child) }}" step="1"
                           placeholder="{{ __('Enter Total Child Number') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="user_id">{{ __('Customer') }}</label>
                    <select name="user_id" class="form-control select2 " id="user_id" required>
                        <option value="0" disabled selected> {{ __('Please Select') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $booking->users->id === $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="discount">{{ __('Discount') }}</label>
                    <input type="number" name="discount" class="form-control" id="discount" min="0"
                           value="{{ old('discount', $booking->discount) }}" step="1"
                           placeholder="{{ __('Please enter Discount') }} "/>
                </div>

                <div class="form-group">
                    <label>{{ __('Total Price : ') }}<span id="fee">RM {{ $booking->total_fee }}</span></label>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button form="editForm" type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const tripId = $('#trip_id');
        const child = $('#child');
        const adult = $('#adult');
        const discount = $('#discount');
        const userID = $('#user_id');

        userID.select2();

        function updatePrice() {
            const adultVal = adult.val();
            const tripIdVal = tripId.val();
            if (adultVal === 0 || tripIdVal === null) {
                return;
            }
            $.ajax({
                type: "POST",
                url: "{{ route('bookings.calculatePrice') }}",
                data: {
                    trip_id: tripId.val(),
                    child: child.val(),
                    adult: adult.val(),
                    discount: discount.val(),
                    user_id: userID.val()
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
