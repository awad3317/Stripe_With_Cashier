<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Direct Checkout - Setup Intent') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('direct.setupIntent.post') }}" method="POST" id="form">
                        @csrf
                        <input type="hidden" name="setup_intent_id" id="payment_intent_id">
                        <!-- Stripe Elements Placeholder -->
                        <div id="card-element"></div>

                        <button id="card-button" class="btn btn-sm btn-primary mt-3"
                            data-secret="{{ $payment->client_secret }}" type="button">
                            Process Payment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>

        // Initialize Stripe
        const stripe = Stripe(@json(env('STRIPE_KEY')));
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        // Handle Payment Method 
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
        cardButton.addEventListener('click', async (e) => {
            const { paymentIntent, error } = await stripe.confirmCardPayment(
                clientSecret, {
                payment_method: {
                    card: cardElement,
                }
            }
            );

            if (error) {
                alert('error');
                console.log(error);
            } else {
                alert('Payment Method Created Successfully!');
                console.log(paymentIntent);
                document.getElementById('payment_intent_id').value = paymentIntent.id;
                document.getElementById('form').submit();
            }
        });
    </script>
</x-app-layout>