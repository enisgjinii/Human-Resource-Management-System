<?php
require_once 'vendor/autoload.php';
require_once 'secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);

header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost/Human-Resource-Management-System/';

$customer_email = $_COOKIE['user_email']; // Assuming 'user_email' is the correct cookie name

try {
    $customer_id = null;

    // List customers and find one with the given email
    $customers = \Stripe\Customer::all(['email' => $customer_email]);
    if (count($customers->data) > 0) {
        $customer_id = $customers->data[0]->id;
    } else {
        // Create a new customer if one doesn't exist
        $new_customer = \Stripe\Customer::create([
            'email' => $customer_email,
            // Add more customer information as needed
        ]);

        // Use the newly created customer's ID
        $customer_id = $new_customer->id;
    }

    // Use existing or newly created customer ID to create checkout session
    $prices = \Stripe\Price::all([
        'lookup_keys' => [$_POST['lookup_key']],
        'expand' => ['data.product']
    ]);

    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price' => $prices->data[0]->id,
            'quantity' => 1,
        ]],
        'customer' => $customer_id, // Use the customer ID here
        'mode' => 'subscription',
        'success_url' => $YOUR_DOMAIN . 'success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $YOUR_DOMAIN . 'cancel.php?session_id={CHECKOUT_SESSION_ID}', // Make sure session_id is passed here
    ]);

    header("HTTP/1.1 303 See Other");
    header("Location: " . $checkout_session->url);
} catch (\Stripe\Exception\ApiErrorException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
