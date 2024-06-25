<?php
include('connection.php');
include('check_auth.php');
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM documents WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $document = mysqli_fetch_assoc($result);
        $file_path = 'uploads/' . $document['file_name'];
        if (file_exists($file_path)) {
            // Set appropriate headers based on file type
            switch ($document['file_type']) {
                case 'pdf':
                    header('Content-Type: application/pdf');
                    break;
                case 'doc':
                case 'docx':
                    header('Content-Type: application/msword');
                    break;
                case 'xls':
                case 'xlsx':
                    header('Content-Type: application/vnd.ms-excel');
                    break;
                case 'jpg':
                case 'jpeg':
                    header('Content-Type: image/jpeg');
                    break;
                case 'png':
                    header('Content-Type: image/png');
                    break;
                case 'gif':
                    header('Content-Type: image/gif');
                    break;
                default:
                    header('Content-Type: application/octet-stream');
            }
            header('Content-Disposition: attachment; filename="' . $document['document_name'] . '.' . $document['file_type'] . '"');
            header('Content-Length: ' . $document['file_size']);
            readfile($file_path);
            exit;
        } else {
            echo "File not found.";
        }
    } else {
        echo "Document not found.";
    }
} else {
    echo "Invalid request.";
}
