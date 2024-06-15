<?php
// Include the Stripe PHP library
require_once('vendor/autoload.php');
require_once 'secrets.php';
// Set your Stripe API secret key
\Stripe\Stripe::setApiKey($stripeSecretKey);
// Initialize variables for messages
$error_message = null;
$success_message = null;
// Validate and sanitize the session_id parameter
if (!isset($_GET['session_id'])) {
    $error_message = "Error: session_id parameter is missing.";
} else {
    $session_id = filter_input(INPUT_GET, 'session_id', FILTER_SANITIZE_STRING);
    if (!$session_id) {
        $error_message = "Error: Invalid session_id parameter.";
    } else {
        try {
            // Retrieve the checkout session to check its status
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            // Ensure the session is associated with the correct customer and context
            // This is where you might add additional validation if needed
            // Check the session status to determine if the payment was canceled
            if ($session->payment_status === 'canceled') {
                $success_message = "Payment canceled. You can try again later.";
                header("Location: index.php");
                exit();
            } else if ($session->payment_status === 'unpaid') {
                $error_message = "Payment not completed. Please try again later.";
                header("Location: index.php");
                exit();
            } else {
                // Handle other statuses as needed (e.g., 'paid', 'unpaid', 'no_payment_required')
                if ($session->payment_status === 'paid') {
                    $success_message = "Payment completed successfully.";
                    header("Location: success.php");
                    exit();
                } else {
                    $error_message = "Unexpected payment status: " . htmlspecialchars($session->payment_status);
                }
            }
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Handle invalid requests, such as an incorrect session ID
            error_log("Invalid Stripe request: " . $e->getMessage());
            $error_message = "Error: Invalid session ID. Please check your input.";
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Handle authentication errors (e.g., incorrect API keys)
            error_log("Stripe authentication error: " . $e->getMessage());
            $error_message = "Error: Authentication with payment gateway failed. Please try again later.";
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Handle network communication errors with Stripe
            error_log("Stripe API connection error: " . $e->getMessage());
            $error_message = "Error: Network communication with payment gateway failed. Please try again later.";
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle general API errors
            error_log("Stripe API error: " . $e->getMessage());
            $error_message = "Error: An error occurred while processing your payment. Please try again later.";
        } catch (Exception $e) {
            // Handle any other types of errors
            error_log("General error: " . $e->getMessage());
            $error_message = "Error: An unexpected error occurred. Please try again later.";
        }
    }
}
// Output error or success messages (if needed)
if ($error_message) {
    echo "<p>$error_message</p>";
}
if ($success_message) {
    echo "<p>$success_message</p>";
}
