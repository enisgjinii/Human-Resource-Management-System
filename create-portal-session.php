<?php
require_once 'vendor/autoload.php';
require_once 'secrets.php';
\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');
$YOUR_DOMAIN = 'http://localhost/Human-Resource-Management-System/';

// Check if the customer_id parameter is provided
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    try {
        // Create a billing portal session
        $session = \Stripe\BillingPortal\Session::create([
            'customer' => $customer_id,
            'return_url' => $YOUR_DOMAIN,
        ]);
        header("HTTP/1.1 303 See Other");
        header("Location: " . $session->url);
    } catch (Error $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Missing customer_id parameter']);
}
