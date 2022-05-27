@push('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        let clientSecret;
        let elements;
        let cardElement;
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        window.addEventListener('getReadyForPayment', (event) => {
            clientSecret = event.detail.clientSecret;

            //create an instance of the card Element that look like bootstrap form
            elements = stripe.elements({
                locale: '{{ app()->getLocale() }}',
            });
            cardElement = elements.create('card');
            cardElement.mount('#card-element');
        });

        function pay() {
            $('#payment-button').attr('disabled', true);
            $('#payment-button-spinner').removeClass('d-none');
            stripe.confirmCardSetup(clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: $('billing-name').val(),
                        },
                    }
                })
                .then(function(result) {
                    if (result.error) {
                        $('#card-error').text(result.error.message).removeClass('d-none');
                        $('#payment-button-spinner').addClass('d-none');
                        $('#payment-button').attr('disabled', false);
                    } else {
                        $('#card-error').text('').addClass('d-none');
                        $('#payment-button-spinner').addClass('d-none');
                        Livewire.emit('cardSetupConfirmed', result.setupIntent.payment_method);
                    }
                });
        }
    </script>
@endpush
