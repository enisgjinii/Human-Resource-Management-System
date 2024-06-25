<?php
require_once('check_auth.php');
require_once('connection.php');

// Use prepared statements to prevent SQL injection
$query = "SELECT * FROM hr_settings LIMIT 1";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$settings = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $company_name = filter_input(INPUT_POST, 'company-name', FILTER_SANITIZE_STRING);
    $company_address = filter_input(INPUT_POST, 'company-address', FILTER_SANITIZE_STRING);
    $enable_dark_mode = isset($_POST['enable-dark-mode']) ? 1 : 0;
    $default_language = filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING);
    $smtp_server = filter_input(INPUT_POST, 'smtp-server', FILTER_SANITIZE_STRING);
    $smtp_port = filter_input(INPUT_POST, 'smtp-port', FILTER_VALIDATE_INT);
    $annual_leave_days = filter_input(INPUT_POST, 'annual-leave', FILTER_VALIDATE_INT);
    $sick_leave_days = filter_input(INPUT_POST, 'sick-leave', FILTER_VALIDATE_INT);
    $pay_cycle = filter_input(INPUT_POST, 'pay-cycle', FILTER_SANITIZE_STRING);
    $default_tax_rate = filter_input(INPUT_POST, 'tax-rate', FILTER_VALIDATE_FLOAT);

    // Prepare the update query with placeholders
    $update_query = "UPDATE hr_settings SET 
                     company_name = ?, company_address = ?, enable_dark_mode = ?,
                     default_language = ?, smtp_server = ?, smtp_port = ?,
                     annual_leave_days = ?, sick_leave_days = ?, pay_cycle = ?,
                     default_tax_rate = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param(
        $stmt,
        "ssississsdi",
        $company_name,
        $company_address,
        $enable_dark_mode,
        $default_language,
        $smtp_server,
        $smtp_port,
        $annual_leave_days,
        $sick_leave_days,
        $pay_cycle,
        $default_tax_rate,
        $settings['id']
    );
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Settings updated successfully!";
    } else {
        $error_message = "Error updating settings: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);

    // Refresh settings after update
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $settings = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentFlow - Settings</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.4.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-200">
    <?php include('layouts/sidebar.php'); ?>
    <div class="p-4 sm:ml-64 mt-14 text-white p-4 rounded-lg">
        <div class="p-4 border border-gray-200 rounded-xl bg-white dark:bg-gray-800 dark:border-gray-700 my-4">
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">HRMS Settings</h2>
            <?php if (isset($success_message)) : ?>
                <div class='p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400' role='alert'>
                    <?= $success_message ?>
                </div>
            <?php endif; ?>
            <?php if (isset($error_message)) : ?>
                <div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400' role='alert'>
                    <?= $error_message ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                <!-- Company Information -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Company Information</h3>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="company-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Company Name</label>
                            <input type="text" id="company-name" name="company-name" value="<?= htmlspecialchars($settings['company_name']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="company-address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Company Address</label>
                            <input type="text" id="company-address" name="company-address" value="<?= htmlspecialchars($settings['company_address']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>
                    </div>
                </div>

                <!-- System Preferences -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">System Preferences</h3>
                    <div class="mb-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable-dark-mode" value="1" class="sr-only peer" <?= $settings['enable_dark_mode'] ? 'checked' : '' ?>>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Enable Dark Mode</span>
                        </label>
                    </div>
                    <div class="mb-4">
                        <label for="language" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Default Language</label>
                        <select id="language" name="language" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="en" <?= $settings['default_language'] == 'en' ? 'selected' : '' ?>>English</option>
                            <option value="es" <?= $settings['default_language'] == 'es' ? 'selected' : '' ?>>Spanish</option>
                            <option value="fr" <?= $settings['default_language'] == 'fr' ? 'selected' : '' ?>>French</option>
                        </select>
                    </div>
                </div>

                <!-- Email Settings -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Email Settings</h3>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="smtp-server" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SMTP Server</label>
                            <input type="text" id="smtp-server" name="smtp-server" value="<?= htmlspecialchars($settings['smtp_server']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="smtp-port" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SMTP Port</label>
                            <input type="number" id="smtp-port" name="smtp-port" value="<?= $settings['smtp_port'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>
                    </div>
                </div>

                <!-- Leave Management -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Leave Management</h3>
                    <div class="mb-4">
                        <label for="annual-leave" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Annual Leave Days</label>
                        <input type="number" id="annual-leave" name="annual-leave" value="<?= $settings['annual_leave_days'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="sick-leave" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sick Leave Days</label>
                        <input type="number" id="sick-leave" name="sick-leave" value="<?= $settings['sick_leave_days'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div>
                </div>

                <!-- Payroll Settings -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Payroll Settings</h3>
                    <div class="mb-4">
                        <label for="pay-cycle" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pay Cycle</label>
                        <select id="pay-cycle" name="pay-cycle" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="weekly" <?= $settings['pay_cycle'] == 'weekly' ? 'selected' : '' ?>>Weekly</option>
                            <option value="biweekly" <?= $settings['pay_cycle'] == 'biweekly' ? 'selected' : '' ?>>Bi-weekly</option>
                            <option value="monthly" <?= $settings['pay_cycle'] == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="tax-rate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Default Tax Rate (%)</label>
                        <input type="number" id="tax-rate" name="tax-rate" value="<?= $settings['default_tax_rate'] ?>" step="0.01" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save All Settings</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>