<?php
// Start the session
session_start();

// Function to log session information to a JSON file
function logSessionInfo($log_file, $session_data)
{
    // Check if the log file exists
    if (!file_exists($log_file)) {
        // Create the log file and set initial content
        file_put_contents($log_file, json_encode(array(), JSON_PRETTY_PRINT));
    }

    // Get current log data
    $current_log = json_decode(file_get_contents($log_file), true);

    // Add the new session data
    $current_log[] = $session_data;

    // Save the updated log data back to the file
    file_put_contents($log_file, json_encode($current_log, JSON_PRETTY_PRINT));
}

try {
    // Collect session data to be logged
    $session_data = array(
        'session_id' => session_id(),
        'user_data' => $_SESSION,
        'logout_time' => date('Y-m-d H:i:s'),
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT']
    );

    // Define the path to the log file
    $log_file = 'session_logs.json';

    // Log session information
    logSessionInfo($log_file, $session_data);

    // Clear all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Clear all cookies by setting their expiration time to the past
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
        // Clear the refresh_token cookie
        if (isset($_COOKIE['refresh_token'])) {
            setcookie(
                'refresh_token',
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
    }

    // Add security headers
    header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; object-src 'none'");
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: DENY");
    header("X-XSS-Protection: 1; mode=block");
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");

    // Redirect to index.php
    header("Location: index.php");
    exit();
} catch (Exception $e) {
    // Log any errors to a separate error log
    error_log($e->getMessage());

    // Optionally display an error message to the user
    echo "An error occurred during logout. Please try again.";
    exit();
}
