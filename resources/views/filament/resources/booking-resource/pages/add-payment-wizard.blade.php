<x-filament::page>
    <link href="{{ asset('booking_css/booking_css.css') }}" rel="stylesheet">
    <livewire:booking.add-payment-on-booking :booking="$booking" />
    @include('smartTT.partials.initialStripeScript')
</x-filament::page>
