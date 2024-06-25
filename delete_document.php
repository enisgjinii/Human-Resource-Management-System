<?php
include('check_auth.php');
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $query = "DELETE FROM documents WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        echo "Document deleted successfully!";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
