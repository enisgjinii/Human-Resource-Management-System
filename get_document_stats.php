<?php
include('connection.php');

$query = "SELECT DATE_FORMAT(uploaded_date, '%Y-%m') as month, COUNT(*) as count FROM documents GROUP BY month";
$result = mysqli_query($conn, $query);

$months = [];
$counts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $months[] = $row['month'];
    $counts[] = $row['count'];
}

echo json_encode(['months' => $months, 'counts' => $counts]);
