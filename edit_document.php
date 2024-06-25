<?php
include('check_auth.php');
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['document_id'];
    $document_name = $_POST['document_name'];

    $query = "UPDATE documents SET document_name = '$document_name' WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        echo "Document updated successfully!";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
