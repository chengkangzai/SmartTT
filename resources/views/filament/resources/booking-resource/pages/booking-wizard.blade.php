<x-filament::page>
    <link href="{{ asset('booking_css/booking_css.css') }}" rel="stylesheet">
    <livewire:booking.create-booking-wizard :package-id="request('package_id')"/>
    @include('smartTT.partials.initialStripeScript')
</x-filament::page>
