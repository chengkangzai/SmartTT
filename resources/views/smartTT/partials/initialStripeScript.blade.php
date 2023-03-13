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
        document.getElementById('payment-button').disabled = true;
        document.getElementById('payment-button-spinner').classList.remove('d-none');
        stripe.confirmCardSetup(clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: document.getElementById('billing-name').value,
                    },
                }
            })
            .then(function(result) {
                if (result.error) {
                    // document.getElementById('card-error').textContent = result.error.message;
                    // document.getElementById('card-error').classList.remove('d-none');
                    document.getElementById('payment-button-spinner').classList.add('d-none');
                    document.getElementById('payment-button').disabled = false;
                } else {
                    // document.getElementById('card-error').textContent = '';
                    // document.getElementById('card-error').classList.add('d-none');
                    document.getElementById('payment-button-spinner').classList.add('d-none');
                    Livewire.emit('cardSetupConfirmed', result.setupIntent.payment_method);
                }
            });

    }
</script>
