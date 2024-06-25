<?php include('check_auth.php'); ?>
<?php include('connection.php'); // include your database connection file 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentFlow</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-200">
    <?php include('layouts/sidebar.php') ?>
    <div class="p-4 sm:ml-64 mt-14 text-white dark:bg-gray-800 p-4 rounded-lg">
        <div class="my-4">
            <button type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Recruitment Dashboard</button>
        </div>
        <!-- Dashboard Section -->
        <div id="recruitment-dashboard">
            <div id="metrics" class="grid grid-cols-1 md:grid-cols-3 gap-4 dark:bg-gray-800">
                <div id="open-positions" class="bg-white p-4 rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                    <h3 class="text-lg text-gray-900 dark:text-gray-200">Open Positions</h3>
                    <div id="open-positions-chart"></div>
                </div>
                <div id="applications-received" class="bg-white p-4 rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                    <h3 class="text-lg text-gray-900 dark:text-gray-200">Applications Received</h3>
                    <div id="applications-received-chart"></div>
                </div>
                <div id="conversion-rate" class="bg-white p-4 rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                    <h3 class="text-lg text-gray-900 dark:text-gray-200">Conversion Rate</h3>
                    <div id="conversion-rate-chart"></div>
                </div>
            </div>
        </div>
        <!-- Job Listings Section -->
        <div id="job-listings" class="mt-8">
            <button type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Job Listings</button>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs mb-2 text-sm font-medium text-gray-900 dark:text-white uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="py-2 px-4 border-b">Job Title</th>
                        <th class="py-2 px-4 border-b">Department</th>
                        <th class="py-2 px-4 border-b">Location</th>
                        <th class="py-2 px-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include('connection.php');
                    $sql = "SELECT * FROM jobs";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700'>";
                        echo "<td class='py-2 px-4 border-b'>{$row['job_title']}</td>";
                        echo "<td class='py-2 px-4 border-b'>{$row['department']}</td>";
                        echo "<td class='py-2 px-4 border-b'>{$row['location']}</td>";
                        echo "<td class='py-2 px-4 border-b'>
                                <a href='view_applications.php?job_id={$row['id']}' class='text-blue-600 hover:underline'>View Applications</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="my-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-styled-tab" data-tabs-toggle="#default-styled-tab-content" data-tabs-active-classes="text-light-600 hover:text-light-600 dark:text-light-500 dark:hover:text-light-500 border-light-600 dark:border-light-500" data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" id="profile-styled-tab" data-tabs-target="#styled-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="dashboard-styled-tab" data-tabs-target="#styled-dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">Dashboard</button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-styled-tab" data-tabs-target="#styled-settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Settings</button>
                </li>
                <li role="presentation">
                    <button class="inline-block text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="contacts-styled-tab" data-tabs-target="#styled-contacts" type="button" role="tab" aria-controls="contacts" aria-selected="false">Contacts</button>
                </li>
            </ul>
        </div>
        <div id="default-styled-tab-content">
            <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-profile" role="tabpanel" aria-labelledby="profile-tab">
                <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Profile tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
            </div>
            <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Dashboard tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
            </div>
            <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-settings" role="tabpanel" aria-labelledby="settings-tab">
                <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Settings tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
            </div>
            <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-contacts" role="tabpanel" aria-labelledby="contacts-tab">
                <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Contacts tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
            </div>
        </div>
        <!-- Add New Job Posting Form -->
        <div id="add-job" class="mt-8 ">
            <button type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Post a New Job</button>
            <form action="post_job.php" method="post" class="bg-white dark:bg-gray-800 dark:text-gray-200">
                <div class="mb-4">
                    <label for="job_title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Job Title</label>
                    <input type="text" id="job_title" name="job_title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="department" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department</label>
                    <input type="text" id="department" name="department" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="location" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location</label>
                    <input type="text" id="location" name="location" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="job_description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Job Description</label>
                    <textarea id="job_description" name="job_description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="requirements" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Requirements</label>
                    <textarea id="requirements" name="requirements" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="employment_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Employment Type</label>
                    <select id="employment_type" name="employment_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <option value="full-time">Full-time</option>
                        <option value="part-time">Part-time</option>
                        <option value="contract">Contract</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="application_deadline" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Application Deadline</label>
                    <input type="date" id="application_deadline" name="application_deadline" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                </div>
                <div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Post Job</button>
                </div>
            </form>
        </div>
    </div>
    <?php include('layouts/footer.php') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="theme.js"></script>
    <script>
        // Sample data for charts
        var options1 = {
            chart: {
                type: 'bar'
            },
            series: [{
                name: 'Open Positions',
                data: [10, 20, 30, 40, 50]
            }]
        };
        var chart1 = new ApexCharts(document.querySelector("#open-positions-chart"), options1);
        chart1.render();
        var options2 = {
            chart: {
                type: 'line'
            },
            series: [{
                name: 'Applications Received',
                data: [15, 25, 35, 45, 55]
            }]
        };
        var chart2 = new ApexCharts(document.querySelector("#applications-received-chart"), options2);
        chart2.render();
        var options3 = {
            chart: {
                type: 'area'
            },
            series: [{
                name: 'Conversion Rate',
                data: [5, 10, 15, 20, 25]
            }]
        };
        var chart3 = new ApexCharts(document.querySelector("#conversion-rate-chart"), options3);
        chart3.render();
    </script>
</body>

</html>