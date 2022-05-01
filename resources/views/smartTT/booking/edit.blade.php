@php
/** @var \App\Models\Booking $booking */
/** @var \App\Models\Package $package */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('Edit Booking') }}
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
                    <label class="form-label" for="package_id">{{ __('Packages') }}</label>
                    {{-- //TODO add package pricing --}}
                    <select name="package_id" class="form-control select2 " id="package_id" required>
                        @foreach ($packages as $key => $package)
                            <option value="{{ $package->id }}" data-price="{{ $package->price }}"
                                @checked($booking->package->id === $key)>
                                {{ $package->tour->name }} ({{ $package->depart_time }}) (${{ $package->price }})
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
                    <label class="form-label" for="child">{{ __('Child') }}
                        <small>{{ __('children that is smaller than 12 years old') }}</small>
                    </label>
                    <input type="number" name="child" class="form-control" id="child" min="0"
                        value="{{ old('child', $booking->child) }}" step="1"
                        placeholder="{{ __('Enter Total Child Number') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="user_id">{{ __('Customer') }}</label>
                    <select name="user_id" class="form-control select2 " id="user_id" required multiple>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @checked($booking->user->id === $user->id)>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="discount">{{ __('Discount') }}</label>
                    <input type="number" name="discount" class="form-control" id="discount" min="0"
                        value="{{ old('discount', $booking->discount) }}" step="1"
                        placeholder="{{ __('Please enter Discount') }} " />
                </div>

                <div class="form-group">
                    <label>{{ __('Total Price : ') }}<span id="fee">RM
                            {{ number_format($booking->total_price) }}</span></label>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button form="editForm" type="submit" class="btn btn-outline-primary">{{ __('Update') }}</button>
        </div>
    </div>
@endsection

@push('script')
    <script>
        const packageId = $('#package_id');
        const child = $('#child');
        const adult = $('#adult');
        const discount = $('#discount');
        const userID = $('#user_id');

        userID.select2({
            maximumSelectionLength: 1,
        });

        function updatePrice() {
            const adultVal = adult.val();
            const packageVal = packageId.val();
            if (adultVal === 0 || packageVal === null) {
                return;
            }
            $.ajax({
                type: "POST",
                url: "{{ route('bookings.calculatePrice') }}",
                data: {
                    package_id: packageId.val(),
                    child: child.val(),
                    adult: adult.val(),
                    discount: discount.val(),
                    user_id: userID.val()
                },
                success: function(response) {
                    $('#fee').text('RM ' + response)
                }
            })
        }

        discount.on('change', updatePrice);
        child.on('change', updatePrice);
        adult.on('change', updatePrice);
        packageId.on('change', updatePrice)
    </script>
@endpush
