@php
/** @var \App\Models\Booking $booking */
/** @var \App\Models\Package $package */
@endphp

@extends('layouts.app')
@section('title')
    {{ __('Create Booking') }}
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">{{ __('Bookings') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>

    <livewire:booking.create-booking-card />
@endsection

@push('script')
    <script>
        $.ajax({
            type: "POST",
            url: "{{ route('select2.user.getCustomer') }}",
            success: function(response) {
                $("#user_id").select2({
                    data: response,
                    maximumSelectionLength: 1
                });
            }
        });

        const packageId = $('#package_id');
        const child = $('#child');
        const adult = $('#adult');
        const discount = $('#discount');

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
                    discount: discount.val()
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
