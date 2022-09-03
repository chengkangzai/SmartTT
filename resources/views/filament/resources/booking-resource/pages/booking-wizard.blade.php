<x-filament::page>
    <link href="{{ asset('booking_css/booking_css.css') }}" rel="stylesheet">
    <livewire:booking.create-booking-wizard :package-id="1"/>
    @include('smartTT.partials.initialStripeScript')
</x-filament::page>
