<?php
include('check_auth.php');
include('connection.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM documents WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    } else {
        echo "No document found";
    }
}
