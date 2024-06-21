<?php
include('check_auth.php');
include('connection.php');

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $result = mysqli_query($conn, "SELECT * FROM applications WHERE job_id = $job_id");

    echo "<h2 class='text-2xl font-bold mb-4'>Applications for Job ID: $job_id</h2>";
    echo "<table class='min-w-full bg-white'>";
    echo "<thead><tr><th class='py-2 px-4 border-b'>Applicant Name</th><th class='py-2 px-4 border-b'>Applicant Email</th><th class='py-2 px-4 border-b'>Status</th><th class='py-2 px-4 border-b'>Actions</th></tr></thead>";
    echo "<tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td class='py-2 px-4 border-b'>{$row['applicant_name']}</td>";
        echo "<td class='py-2 px-4 border-b'>{$row['applicant_email']}</td>";
        echo "<td class='py-2 px-4 border-b'>{$row['status']}</td>";
        echo "<td class='py-2 px-4 border-b'>
                <a href='view_application.php?id={$row['id']}' class='text-blue-600 hover:underline'>View</a>
              </td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
    $conn->close();
} else {
    echo "Job ID is not set.";
}
