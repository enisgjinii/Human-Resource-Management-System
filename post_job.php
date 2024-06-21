<?php
include('check_auth.php');
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_title = $_POST['job_title'];
    $department = $_POST['department'];
    $location = $_POST['location'];
    $job_description = $_POST['job_description'];
    $requirements = $_POST['requirements'];
    $employment_type = $_POST['employment_type'];
    $application_deadline = $_POST['application_deadline'];

    $stmt = $conn->prepare("INSERT INTO jobs (job_title, department, location, job_description, requirements, employment_type, application_deadline) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $job_title, $department, $location, $job_description, $requirements, $employment_type, $application_deadline);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: recruitment.php");
    exit();
}
