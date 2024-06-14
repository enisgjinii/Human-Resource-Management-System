<?php
session_start();
include('connection.php'); // Include your database connection file

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Check if the refresh token is available in the cookie
if (!isset($_COOKIE['refresh_token'])) {
    // Redirect the user to another page, such as a login page
    header("Location: index.php");
    exit; // Stop further execution of the script
}
