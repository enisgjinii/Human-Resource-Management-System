<?php
include('connection.php');
session_start();
$id = intval($_POST['id']);
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);
$dob = trim($_POST['dob']);
$position = trim($_POST['position']);
$department = trim($_POST['department']);
$hire_date = trim($_POST['hire_date']);
$salary = trim($_POST['salary']);
$emergency_contact = trim($_POST['emergency_contact']);
$education_history = trim($_POST['education_history']);
$skills_certifications = trim($_POST['skills_certifications']);

// Validation
$errors = [];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}
if (!preg_match("/^\+\d{3} \(\d{1}\) \d{3} \d{3} \d{3}$/", $phone)) {
    $errors[] = "Invalid phone number format.";
}
if (new DateTime($hire_date) > new DateTime()) {
    $errors[] = "Hire date cannot be in the future.";
}
if (!empty($salary) && !preg_match("/^\d+(\.\d{1,2})?$/", $salary)) {
    $errors[] = "Invalid salary format.";
}

if (!empty($errors)) {
    echo implode("<br>", $errors);
    exit;
}

// File upload handling
$profile_picture = '';
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
    $fileName = $_FILES['profile_picture']['name'];
    $fileSize = $_FILES['profile_picture']['size'];
    $fileType = $_FILES['profile_picture']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
    if (in_array($fileExtension, $allowedfileExtensions)) {
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $profile_picture = $newFileName;
        } else {
            echo 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
        }
    } else {
        echo 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
    }
}

$stmt = $conn->prepare("UPDATE `employees` SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, dob = ?, position = ?, department = ?, hire_date = ?, salary = ?, emergency_contact = ?, education_history = ?, skills_certifications = ?, profile_picture = ? WHERE id = ?");
$stmt->bind_param("ssssssssssssssi", $first_name, $last_name, $email, $phone, $address, $dob, $position, $department, $hire_date, $salary, $emergency_contact, $education_history, $skills_certifications, $profile_picture, $id);
$stmt->execute();

$change_description = "Updated employee details";
// Get user_name and user_surname from cookies
$changed_by = $_COOKIE['user_name'] . " " . $_COOKIE['user_surname'];
$change_stmt = $conn->prepare("INSERT INTO `employee_changes` (employee_id, change_description, changed_by) VALUES (?, ?, ?)");
$change_stmt->bind_param("iss", $id, $change_description, $changed_by);
$change_stmt->execute();

header("Location: edit_employee.php?id=$id");
