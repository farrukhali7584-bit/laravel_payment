import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

window.submitLogout = function (event, element) {
    event.preventDefault();
    element.closest("form").submit();
};

window.stripe_checkout = function () {
    window.axios
        .post("/stripe_checkout", { amount: 50 })
        .then((response) => {
            // Assuming the backend returns { url: 'https://checkout.stripe.com/...' }
            if (response.data.url) {
                window.location.href = response.data.url;
            }
        })
        .catch((error) => {
            console.error("Stripe checkout error:", error);
        });
};
