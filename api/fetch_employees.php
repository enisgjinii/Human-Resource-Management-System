<?php
include '../connection.php';

$sql = "SELECT id, CONCAT(first_name, ' ', last_name) AS name, email, phone, address, position, department, hire_date, salary, created_at FROM employees";
$result = mysqli_query($conn, $sql);

$employees = array();
while ($row = mysqli_fetch_assoc($result)) {
    $employees[] = $row;
}

echo json_encode(array('data' => $employees));

mysqli_close($conn);
