// A reference to Stripe.js initialized with your real test publishable API key.
var stripe = Stripe("pk_test_51JVImyLtSNYQRudIEPWRZgHqOvYHvAhKHnMXp5AA4Xh9EqihbyP4zJicSh4LiL9kDXIly8sIzJ8jXDC6QZg7osar00054XHAOp");
console.log('here');
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

var card = elements.create("card", { style: style });
// Stripe injects an iframe into the DOM
card.mount("#card-element");

