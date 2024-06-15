<?php
require_once 'vendor/autoload.php';
$config = require 'config.php';
$client = new Google_Client();
$client->setClientId($config['clientID']);
$client->setClientSecret($config['clientSecret']);
$client->setRedirectUri($config['redirectUri']);
$client->setAccessType('offline'); // Ensure offline access to get a refresh token
$client->setIncludeGrantedScopes(true); // Incremental auth
$client->addScope("email");
$client->addScope("profile");
// $client->addScope("https://www.googleapis.com/auth/calendar");
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);
    // Ensure the refresh token is retrieved
    $refreshToken = isset($token['refresh_token']) ? $token['refresh_token'] : null;
    // Get profile info
    $google_oauth = new Google\Service\Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $oauthId = $google_account_info->id;
    $name = $google_account_info->givenName;
    $surname = $google_account_info->familyName;
    $email = $google_account_info->email;
    $accessToken = $token['access_token'];
    // Get profile  picture
    $picture = $google_account_info->picture;
    // Set refresh token in a cookie
    setcookie('refresh_token', $refreshToken, time() + (10 * 365 * 24 * 60 * 60), '/'); // Expires in 10 years
    setcookie('user_email', $email, time() + (10 * 365 * 24 * 60 * 60), '/'); // Expires in 10 years
    require 'db.php';
    // Check if the user already exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        // User doesn't exist, insert a new record
        $stmt = $pdo->prepare("INSERT INTO users (oauth_id, name, surname, email, access_token, refresh_token, profile_picture_url) VALUES (:oauth_id, :name, :surname, :email, :access_token, :refresh_token, :profile_picture_url)");
        $stmt->execute([
            'oauth_id' => $oauthId,
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'profile_picture_url' => $picture // Save profile picture URL
        ]);
    } else {
        // User already exists, update access token and refresh token
        $stmt = $pdo->prepare("UPDATE users SET access_token = :access_token, refresh_token = :refresh_token WHERE email = :email");
        $stmt->execute([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'email' => $email
        ]);
    }
    // Redirect to dashboard.php
    header("Location: dashboard.php");
    exit;
}
