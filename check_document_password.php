<?php
function checkDocumentPassword($conn, $document_id, $provided_password)
{
    $query = "SELECT password FROM documents WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $document_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['password'] === null) {
            return true; // Document is not password-protected
        } else {
            return password_verify($provided_password, $row['password']);
        }
    }

    return false; // Document not found
}
