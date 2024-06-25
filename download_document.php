<?php
include('connection.php');
include('check_document_password.php'); // Adjust this line as per your actual check document password script

if (isset($_GET['id'])) {
    $document_id = $_GET['id'];

    // Retrieve document details
    $query = "SELECT * FROM documents WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $document_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $document = $result->fetch_assoc();

        // Check if password is required
        if ($document['password'] !== null) {
            // Password required, check if it's provided
            if (!isset($_POST['password'])) {
                // Show password prompt
                echo '<form method="POST">
                        <input type="password" name="password" required>
                        <input type="submit" value="Submit Password">
                      </form>';
                exit;
            } else {
                // Verify password
                if (!checkDocumentPassword($conn, $document_id, $_POST['password'])) {
                    echo "Incorrect password.";
                    exit;
                }
            }
        }

        // Password check passed or not required, proceed with download
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
