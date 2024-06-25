<?php
include('connection.php');
include('check_auth.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $document_name = mysqli_real_escape_string($conn, $_POST['document_name']);
    $file = $_FILES['document_file'];
    // Get file details
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    // Get file extension
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    // Allowed file types
    $allowed = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx');
    if (in_array($file_ext, $allowed)) {
        if ($file_error === 0) {
            if ($file_size <= 5000000) { // 5MB max file size
                $file_name_new = uniqid('', true) . '.' . $file_ext;
                $file_destination = 'uploads/' . $file_name_new;
                if (move_uploaded_file($file_tmp, $file_destination)) {
                    $sql = "INSERT INTO documents (document_name, file_type, file_size, file_path, uploaded_date) 
                            VALUES ('$document_name', '$file_ext', $file_size, '$file_name_new', NOW())";
                    if (mysqli_query($conn, $sql)) {
                        echo "File uploaded successfully.";
                    } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                    }
                } else {
                    echo "There was an error uploading your file.";
                }
            } else {
                echo "Your file is too large.";
            }
        } else {
            echo "There was an error uploading your file.";
        }
    } else {
        echo "You cannot upload files of this type.";
    }
}
