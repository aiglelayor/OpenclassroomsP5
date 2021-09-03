@extends('layouts.master')

@section('extra-script')
<script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
<div class="col-md-12">
    <h1>Page de Paiement</h1>
    <div class="row">
        <div class="col-md-6">
            <form id="payment-form" class="my-4">
                <div id="card-element">
                    <!--Stripe.js injects the Card Element-->
                </div>
                <p id="card-error" class="my-2" role="alert"></p>
                <button class="btn btn-success mt-4" id="submit">
                    <div class="spinner hidden" id="spinner"></div>
                    <span id="button-text">Procéder au paiement</span>
                </button>
                <p class="result-message hidden">
                    Payment succeeded, see the result in your
                    <a href="" target="_blank">Stripe dashboard.</a> Refresh the page to pay again.
                </p>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<!-- <script src="../checkout/checkout.js"></script> -->

<script>
    // A reference to Stripe.js initialized with your real test publishable API key.
    var stripe = Stripe("pk_test_51JVImyLtSNYQRudIEPWRZgHqOvYHvAhKHnMXp5AA4Xh9EqihbyP4zJicSh4LiL9kDXIly8sIzJ8jXDC6QZg7osar00054XHAOp");

    // The items the customer wants to buy
    var elements = stripe.elements();

    var style = {
        base: {
            color: "#32325d",
            fontFamily: 'Arial, sans-serif',
            fontSmoothing: "antialiased",
            fontSize: "16px",
            "::placeholder": {
                color: "#32325d"
            }
        },
        invalid: {
            fontFamily: 'Arial, sans-serif',
            color: "#fa755a",
            iconColor: "#fa755a"
        }
    };

    var card = elements.create("card", {
        style: style
    });
    // Stripe injects an iframe into the DOM
    card.mount("#card-element");

    card.addEventListener('change', ({
        error
    }) => {
        document.querySelector("button").disabled = event.empty;
        const displayError = document.querySelector("#card-error");
        if (error) {
            displayError.textContent = error.message;
            displayError.classList.add('alert', 'alert-warning');
        } else {
            displayError.textContent = '';
            displayError.classList.remove('alert', 'alert-warning');
        }
    })

    // card.on("change", function (event) {
    // // Disable the Pay button if there are no card details in the Element
    // document.querySelector("button").disabled = event.empty;
    // document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
    // });

    var form = document.getElementById("payment-form");
    form.addEventListener("submit", function(event) {
    event.preventDefault();
    // Complete payment when the submit button is clicked
    
    payWithCard(stripe, card, "{{ $clientSecret }}");
    });

    // Calls stripe.confirmCardPayment
    // If the card requires authentication Stripe shows a pop-up modal to
    // prompt the user to enter authentication details without leaving your page.
    var payWithCard = function(stripe, card, clientSecret) {
        //loading(true);
        stripe
            .confirmCardPayment("{{ $clientSecret }}", {
                payment_method: {
                    card: card
                }
            })
            .then(function(result) {
                if (result.error) {
                    // Show error to your customer
                    //showError(result.error.message);
                    console.log(result.error.message);
                } else {
                    // The payment succeeded!
                    //orderComplete(result.paymentIntent.id);
                    if (result.paymentIntent.status === 'succeeded')
                    {
                        console.log(result.paymentIntent)
                    }
                }
            });
    };
</script>
@endsection