<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Replace with your Spotify API credentials
$client_id = '238e193a21fe498d8686e62132e71ee5';
$client_secret = '19482a6fab1e4284a924e43f21df746c';
$redirect_uri = 'http://localhost/Human-Resource-Management-System/callback.php'; // Update with your callback URL

// Step 1: Redirect to Spotify login to get authorization code
if (!isset($_SESSION['access_token']) && !isset($_GET['code'])) {
    $auth_url = 'https://accounts.spotify.com/authorize?client_id=' . $client_id . '&response_type=code&redirect_uri=' . urlencode($redirect_uri) . '&scope=user-read-currently-playing';
    header('Location: ' . $auth_url);
    exit();
}

// Step 2: Get access token using authorization code
if (isset($_GET['code'])) {
    $token_url = 'https://accounts.spotify.com/api/token';
    $code = $_GET['code'];

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
    $_SESSION['access_token'] = $data['access_token'];
    // Redirect to remove the code parameter from the URL
    // Fix 
    // header("Location: 'http://localhost/Human-Resource-Management-System/spotify.php'");
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Step 3: Use access token to get currently playing track
if (isset($_SESSION['access_token'])) {
    $api_url = 'https://api.spotify.com/v1/me/player/currently-playing';
    $access_token = $_SESSION['access_token'];

    $curl = curl_init($api_url);
    $headers = array(
        'Authorization: Bearer ' . $access_token,
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($response, true);

    if (isset($data['error'])) {
        echo json_encode(array('error' => $data['error']['message']));
    } elseif ($data && isset($data['item'])) {
        $song = $data['item']['name'];
        $artist = $data['item']['artists'][0]['name'];
        $album = $data['item']['album']['name'];
        $albumArt = $data['item']['album']['images'][0]['url'];
        $duration_ms = $data['item']['duration_ms'];
        $timestamp = $data['timestamp'];

        echo json_encode(array(
            'song' => $song,
            'artist' => $artist,
            'album' => $album,
            'albumArt' => $albumArt,
            'duration_ms' => $duration_ms,
            'timestamp' => $timestamp
        ));
    } else {
        echo json_encode(array('error' => 'No track currently playing.'));
    }
} else {
    echo json_encode(array('error' => 'Access token not set.'));
}
