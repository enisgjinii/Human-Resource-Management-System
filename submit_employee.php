<?php
include('check_auth.php');

// Database configuration
include('connection.php');

// Function to sanitize user input
function sanitize_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $address = sanitize_input($_POST['address']);
    $dob = sanitize_input($_POST['dob']);
    $position = sanitize_input($_POST['position']);
    $department = sanitize_input($_POST['department']);
    $hire_date = sanitize_input($_POST['hire_date']);
    $salary = sanitize_input($_POST['salary']);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO employees (first_name, last_name, email, phone, address, dob, position, department, hire_date, salary) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssd", $first_name, $last_name, $email, $phone, $address, $dob, $position, $department, $hire_date, $salary);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New employee registered successfully";
        header('Location: employees.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <style>
        .success-message {
            color: green;
            font-weight: bold;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">Register New Employee</h2>
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
            <div class="<?php echo $stmt->error ? 'error-message' : 'success-message'; ?>">
                <?php echo $stmt->error ? 'Error: ' . $stmt->error : 'New employee registered successfully'; ?>
            </div>
        <?php endif; ?>
        <a href="employees.php" class="text-blue-700 hover:underline">Back to Employee List</a>
    </div>
</body>

</html>