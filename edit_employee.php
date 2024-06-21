<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentFlow - Edit Employee</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-200">
    <?php
    include('layouts/sidebar.php');
    include('connection.php');
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM `employees` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $first_name = htmlspecialchars($row['first_name']);
    $last_name = htmlspecialchars($row['last_name']);
    $email = htmlspecialchars($row['email']);
    $phone = htmlspecialchars($row['phone']);
    $address = htmlspecialchars($row['address']);
    $dob = htmlspecialchars($row['dob']);
    $position = htmlspecialchars($row['position']);
    $department = htmlspecialchars($row['department']);
    $hire_date = htmlspecialchars($row['hire_date']);
    $salary = htmlspecialchars($row['salary']);
    $emergency_contact = htmlspecialchars($row['emergency_contact']);
    $education_history = htmlspecialchars($row['education_history']);
    $skills_certifications = htmlspecialchars($row['skills_certifications']);
    $profile_picture = htmlspecialchars($row['profile_picture']);
    ?>
    <div class="p-4 sm:ml-64 dark:bg-gray-800 mt-14">
        <div class="mt-4">
            <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl p-6">
                <h2 class="text-2xl font-bold mb-6">Edit Employee: <?php echo $first_name . ' ' . $last_name; ?></h2>
                <form id="edit-employee-form" action="update_employee.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200" required>
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium">Email</label>
                            <input type="email" name="email" id="email" value="<?php echo $email; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200" required>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium">Phone</label>
                            <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" pattern="^\+\d{3} \(\d{1}\) \d{3} \d{3} \d{3}$" title="Please enter a valid phone number in the format +000 (0) 000 000 000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200" required>
                        </div>
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium">Address</label>
                        <input type="text" name="address" id="address" value="<?php echo $address; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200" required>
                    </div>
                    <div>
                        <label for="dob" class="block text-sm font-medium">Date of Birth</label>
                        <input type="date" name="dob" id="dob" value="<?php echo $dob; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200" max="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div>
                        <label for="position" class="block text-sm font-medium">Position</label>
                        <input type="text" name="position" id="position" value="<?php echo $position; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200" required>
                    </div>
                    <div>
                        <label for="department" class="block text-sm font-medium">Department</label>
                        <input type="text" name="department" id="department" value="<?php echo $department; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200" required>
                    </div>
                    <div>
                        <label for="hire_date" class="block text-sm font-medium">Hire Date</label>
                        <input type="date" name="hire_date" id="hire_date" value="<?php echo $hire_date; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200" max="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div>
                        <label for="salary" class="block text-sm font-medium">Salary</label>
                        <input type="text" name="salary" id="salary" value="<?php echo $salary; ?>" pattern="^\d+(\.\d{1,2})?$" title="Please enter a valid salary amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200" required>
                    </div>
                    <div>
                        <label for="emergency_contact" class="block text-sm font-medium">Emergency Contact</label>
                        <input type="text" name="emergency_contact" id="emergency_contact" value="<?php echo $emergency_contact; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                    </div>
                    <div>
                        <label for="education_history" class="block text-sm font-medium">Education History</label>
                        <textarea name="education_history" id="education_history" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"><?php echo $education_history; ?></textarea>
                    </div>
                    <div>
                        <label for="skills_certifications" class="block text-sm font-medium">Skills and Certifications</label>
                        <textarea name="skills_certifications" id="skills_certifications" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"><?php echo $skills_certifications; ?></textarea>
                    </div>
                    <div>
                        <label for="profile_picture" class="block text-sm font-medium">Profile Picture</label>
                        <input type="file" name="profile_picture" id="profile_picture" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Save Changes</button>
                    </div>
                </form>
                <hr class="my-8">
                <div class="bg-white">
                    <h3>Change History</h3>
                    <br>
                    <ul class="space-y-4">
                        <?php
                        $history_stmt = $conn->prepare("SELECT * FROM `employee_changes` WHERE employee_id = ? ORDER BY change_date DESC");
                        $history_stmt->bind_param("i", $id);
                        $history_stmt->execute();
                        $history_result = $history_stmt->get_result();
                        while ($history_row = $history_result->fetch_assoc()) {
                            echo "<li class='flex items-center space-x-4'>";
                            echo "<div class='bg-blue-100 text-blue-600 font-semibold px-3 py-1 rounded-md'>" . htmlspecialchars($history_row['change_date']) . "</div>";
                            echo "<div class='flex-1'>";
                            echo "<p class='text-gray-800'>" . htmlspecialchars($history_row['change_description']) . "</p>";
                            echo "<p class='text-sm text-gray-500'>Changed by " . htmlspecialchars($history_row['changed_by']) . "</p>";
                            echo "</div>";
                            echo "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="theme.js"></script>
    <script>
        document.getElementById('edit-employee-form').addEventListener('submit', function(event) {
            event.preventDefault();
            if (this.checkValidity()) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to save the changes?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                })
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Please ensure all fields are filled out correctly.',
                    icon: 'error'
                });
            }
        });
    </script>
</body>
</html>