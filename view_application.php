<?php
include('check_auth.php');
include('connection.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM applications WHERE id = $id");

    if ($row = mysqli_fetch_assoc($result)) {
        echo "<h2 class='text-2xl font-bold mb-4'>Application Details</h2>";
        echo "<p><strong>Name:</strong> {$row['applicant_name']}</p>";
        echo "<p><strong>Email:</strong> {$row['applicant_email']}</p>";
        echo "<p><strong>Resume:</strong> {$row['resume']}</p>";
        echo "<p><strong>Cover Letter:</strong> {$row['cover_letter']}</p>";
        echo "<p><strong>Status:</strong> {$row['status']}</p>";
        echo "<form action='update_application_status.php' method='post'>";
        echo "<input type='hidden' name='id' value='{$row['id']}'>";
        echo "<label for='status' class='block text-gray-700'>Status</label>";
        echo "<select id='status' name='status' class='w-full px-4 py-2 border rounded-lg' required>";
        echo "<option value='Pending'>Pending</option>";
        echo "<option value='Reviewed'>Reviewed</option>";
        echo "<option value='Interviewed'>Interviewed</option>";
        echo "<option value='Hired'>Hired</option>";
        echo "</select>";
        echo "<button type='submit' class='px-4 py-2 bg-blue-600 text-white rounded-lg mt-4'>Update Status</button>";
        echo "</form>";
    } else {
        echo "Application not found.";
    }

    $conn->close();
} else {
    echo "Application ID is not set.";
}
