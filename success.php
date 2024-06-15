<?php

require_once 'vendor/autoload.php';
require_once 'secrets.php'; // This should contain your DB connection details and Stripe secret key
require_once('connection.php');

\Stripe\Stripe::setApiKey($stripeSecretKey);

header('Content-Type: application/json');

try {
    // Validate and sanitize the session_id parameter
    if (!isset($_GET['session_id']) || empty($_GET['session_id'])) {
        throw new Exception("Error: session_id parameter is missing or empty.");
    }

    $session_id = filter_input(INPUT_GET, 'session_id', FILTER_SANITIZE_STRING);

    if (!$session_id) {
        throw new Exception("Error: Invalid session_id parameter.");
    }

    // Fetch the checkout session from Stripe
    $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);

    if (!$checkout_session || !$checkout_session->subscription) {
        throw new Exception("Error: Invalid checkout session or no subscription found.");
    }

    // Retrieve the subscription from the checkout session
    $subscription = \Stripe\Subscription::retrieve($checkout_session->subscription);

    // Extract subscription details
    $subscription_details = [
        'subscription_id' => $subscription->id,
        'customer_id' => $subscription->customer,
        'status' => $subscription->status,
        'current_period_start' => date("Y-m-d H:i:s", $subscription->current_period_start),
        'current_period_end' => date("Y-m-d H:i:s", $subscription->current_period_end),
        'created' => date("Y-m-d H:i:s", $subscription->created),
        'plan_id' => $subscription->plan->id,
        'plan_name' => $subscription->plan->nickname ?? null,
        'amount' => $subscription->plan->amount / 100,
        'currency' => strtoupper($subscription->plan->currency),
        'quantity' => $subscription->quantity,
        'cancel_at_period_end' => $subscription->cancel_at_period_end,
        'canceled_at' => $subscription->canceled_at ? date("Y-m-d H:i:s", $subscription->canceled_at) : null,
        'cancel_at' => $subscription->cancel_at ? date("Y-m-d H:i:s", $subscription->cancel_at) : null,
        'trial_start' => $subscription->trial_start ? date("Y-m-d H:i:s", $subscription->trial_start) : null,
        'trial_end' => $subscription->trial_end ? date("Y-m-d H:i:s", $subscription->trial_end) : null,
    ];

    // Fetch customer details if needed
    $customer = \Stripe\Customer::retrieve($subscription->customer);
    $customer_details = [
        'email' => $customer->email,
        'name' => $customer->name,
        'phone' => $customer->phone,
        'address' => $customer->address,
    ];

    // Update or insert user data into the database
    $email = $conn->real_escape_string($customer_details['email']);
    $sql_select = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        // User exists, update their subscription details
        $update_sql = "
            UPDATE users SET
            stripe_customer_id = '{$subscription_details['customer_id']}',
            subscription_id = '{$subscription_details['subscription_id']}',
            subscription_status = '{$subscription_details['status']}',
            subscription_start = '{$subscription_details['current_period_start']}',
            subscription_end = '{$subscription_details['current_period_end']}',
            subscription_plan_id = '{$subscription_details['plan_id']}',
            subscription_amount = '{$subscription_details['amount']}',
            subscription_currency = '{$subscription_details['currency']}',
            subscription_quantity = '{$subscription_details['quantity']}',
            subscription_trial_start = '{$subscription_details['trial_start']}',
            subscription_trial_end = '{$subscription_details['trial_end']}'
            WHERE email='$email'
        ";

        if ($conn->query($update_sql) === TRUE) {
            echo json_encode([
                'success' => true,
                'message' => 'User subscription details updated successfully.',
                'subscription_details' => $subscription_details,
                'customer_details' => $customer_details,
                'session_id' => $session_id,
            ]);
        } else {
            throw new Exception("Error updating record: " . $conn->error);
        }
    } else {
        // User does not exist, insert their subscription details
        $insert_sql = "
            INSERT INTO users (email, stripe_customer_id, subscription_id, subscription_status, subscription_start, subscription_end, subscription_plan_id, subscription_amount, subscription_currency, subscription_quantity, subscription_trial_start, subscription_trial_end)
            VALUES ('$email', '{$subscription_details['customer_id']}', '{$subscription_details['subscription_id']}', '{$subscription_details['status']}', '{$subscription_details['current_period_start']}', '{$subscription_details['current_period_end']}', '{$subscription_details['plan_id']}', '{$subscription_details['amount']}', '{$subscription_details['currency']}', '{$subscription_details['quantity']}', '{$subscription_details['trial_start']}', '{$subscription_details['trial_end']}')
        ";

        if ($conn->query($insert_sql) === TRUE) {
            echo json_encode([
                'success' => true,
                'message' => 'New user subscription details inserted successfully.',
                'subscription_details' => $subscription_details,
                'customer_details' => $customer_details,
                'session_id' => $session_id,
            ]);
        } else {
            throw new Exception("Error inserting record: " . $conn->error);
        }
    }
} catch (\Stripe\Exception\InvalidRequestException $e) {
    http_response_code(400);
    error_log("Invalid request to Stripe: " . $e->getMessage());
    echo json_encode(['error' => "Invalid request. Please check the session ID and try again."]);
} catch (\Stripe\Exception\AuthenticationException $e) {
    http_response_code(401);
    error_log("Stripe authentication error: " . $e->getMessage());
    echo json_encode(['error' => "Authentication with payment gateway failed."]);
} catch (\Stripe\Exception\ApiConnectionException $e) {
    http_response_code(502);
    error_log("Stripe API connection error: " . $e->getMessage());
    echo json_encode(['error' => "Network communication with payment gateway failed."]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    http_response_code(500);
    error_log("Stripe API error: " . $e->getMessage());
    echo json_encode(['error' => "An error occurred while processing your request. Please try again later."]);
} catch (Exception $e) {
    http_response_code(500);
    error_log("General error: " . $e->getMessage());
    echo json_encode(['error' => "An unexpected error occurred. Please try again later."]);
} finally {
    $conn->close();
}
