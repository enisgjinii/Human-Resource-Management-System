<?php
include('connection.php');
function highlightText($text, $search)
{
    if (empty($search)) {
        return $text;
    }
    return preg_replace("/($search)/i", '<span class="bg-yellow-200 dark:bg-yellow-600">$1</span>', $text);
}
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$query = "SELECT * FROM documents WHERE document_name LIKE '%$search%'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $highlightedName = highlightText($row['document_name'], $search);
?>
    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
        <td class='px-6 py-4'><?php echo $row['id']; ?></td>
        <td class='px-6 py-4'><?php echo $highlightedName; ?></td>
        <td class='px-6 py-4'><?php echo strtoupper($row['file_type']); ?></td>
        <td class='px-6 py-4'><?php echo round($row['file_size'] / 1024, 2); ?> KB</td>
        <td class='px-6 py-4'><?php echo $row['uploaded_date']; ?></td>
        <td class='px-6 py-4'>
            <!-- View Button -->
            <button data-modal-target="viewDocumentModal<?php echo $row['id']; ?>" data-modal-toggle="viewDocumentModal<?php echo $row['id']; ?>" class='text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700'>
                <i class="fi fi-rr-document me-2"></i>
                View
            </button>
            <!-- Edit Button -->
            <button data-modal-target="editDocumentModal<?php echo $row['id']; ?>" data-modal-toggle="editDocumentModal<?php echo $row['id']; ?>" class='text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700'>
                <i class="fi fi-rr-edit me-2"></i>
                Edit</button>
            <!-- Delete Button -->
            <button data-modal-target="deleteDocumentModal<?php echo $row['id']; ?>" data-modal-toggle="deleteDocumentModal<?php echo $row['id']; ?>" class='text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700'>
                <i class="fi fi-rr-trash me-2"></i>
                Delete</button>
            <!-- Download Button -->
        </td>
    </tr>
<?php
}
?>