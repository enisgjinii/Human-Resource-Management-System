<?php
session_start();

// Replace with your Spotify API credentials
$client_id = '238e193a21fe498d8686e62132e71ee5';
$client_secret = '19482a6fab1e4284a924e43f21df746c';
$redirect_uri = 'http://localhost/Human-Resource-Management-System/callback.php'; // Update with your callback URL

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Step 2: Get access token using authorization code
    $token_url = 'https://accounts.spotify.com/api/token';
    $token_request = array(
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirect_uri,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
    );

    $curl = curl_init($token_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($token_request));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($response, true);

    // Store access token in session for further use
    if (isset($data['access_token'])) {
        $_SESSION['access_token'] = $data['access_token'];
        header('Location: tasks.php'); // Redirect back to your main page or wherever needed
    } else {
        echo 'Error fetching access token: ' . $data['error'];
    }
} else {
    echo 'Authorization code not found.';
}
