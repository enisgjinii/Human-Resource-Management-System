<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentFlow</title>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <!-- Add favicon -->
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-200">
    <?php include('layouts/sidebar.php') ?>
    <div class="p-4 sm:ml-64 dark:bg-gray-800 mt-14">
        <div class="mt-4">
            <button type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-xl text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" data-modal-target="crud-modal" data-modal-toggle="crud-modal">
                <i class="fi fi-rr-add mr-2"></i>
                Add Employee
            </button>
            <button id="export-csv" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-xl text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                <i class="fi fi-rr-file-download mr-2"></i>
                Export CSV
            </button>
            <div class="flex justify-between mb-2">
                <div class="items-center">
                    <input type="text" id="search" placeholder="Search..." class="border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-xl py-2 px-4 w-1/3 shadow-sm dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-600">
                </div>
                <div class="flex justify-end items-center">
                    <!-- <label for="length-menu" class="text-sm font-medium text-gray-600">Show:</label> -->
                    <select id="length-menu" class="border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-xl py-2 px-4 w-1/3 shadow-sm dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 dark:border-gray-600">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="all">All</option>
                    </select>
                </div>
            </div>
            <div class="rounded-xl bg-white dark:bg-gray-800 dark:text-white">
                <!-- Main modal -->
                <div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-4xl max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Add Employee
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <form class="p-4 md:p-5" action="submit_employee.php" method="POST">
                                <div class="grid gap-4 mb-4 grid-cols-2">
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First Name</label>
                                        <input type="text" name="first_name" id="first-name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type employee first name" required="">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="last-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last Name</label>
                                        <input type="text" name="last_name" id="last-name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type employee last name" required="">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type employee email" required="">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                                        <input type="tel" name="phone" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type employee phone number" required="">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                                        <input type="text" name="address" id="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type employee address" required="">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="dob" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date of Birth</label>
                                        <input type="date" name="dob" id="dob" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="position" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Position</label>
                                        <input type="text" name="position" id="position" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type employee position" required="">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="department" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department</label>
                                        <input type="text" name="department" id="department" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type employee department" required="">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="hire-date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hire Date</label>
                                        <input type="date" name="hire_date" id="hire-date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="salary" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Salary</label>
                                        <input type="number" name="salary" id="salary" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type employee salary" required="">
                                    </div>
                                </div>
                                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Add New Employee
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="py-3 px-6 text-left">First and Last name</th>
                                <th class="py-3 px-6 text-left">Email</th>
                                <th class="py-3 px-6 text-left">Phone</th>
                                <th class="py-3 px-6 text-left">Address</th>
                                <th class="py-3 px-6 text-left">Position</th>
                                <th class="py-3 px-6 text-left">Department</th>
                                Hire Date
                                <th class="py-3 px-6 text-left">Salary</th>
                                <th class="py-3 px-6 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employees-table-body">
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <button id="prev-page" class="bg-gray-200 hover:bg-gray-300 text-gray-600 border rounded-lg py-2 px-4">Previous</button>
                    <span id="page-info" class="text-gray-600"></span>
                    <button id="next-page" class="bg-gray-200 hover:bg-gray-300 text-gray-600 border rounded-lg py-2 px-4">Next</button>
                </div>
                <div id="loading" class="hidden fixed top-0 left-0 w-full h-full bg-gray-900 opacity-50 flex justify-center items-center">
                    <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-24 w-24"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let employees = [];
        let filteredEmployees = [];
        let currentPage = 1;
        let rowsPerPage = 10; // Changed to let for reassignment
        document.addEventListener('DOMContentLoaded', function() {
            fetchEmployees();
            // Search input debounce
            let debounceTimer;
            document.getElementById('search').addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    const searchTerm = document.getElementById('search').value.toLowerCase();
                    filterEmployees(searchTerm);
                }, 300);
            });
            // Export CSV button click event handler
            document.getElementById('export-csv').addEventListener('click', function() {
                // Add datetime to filename
                const filename = `employees-${new Date().toISOString()}.csv`;
                // Call exportTableToCSV function
                exportTableToCSV(filename);
                // exportTableToCSV('employees.csv');
            });
            // Length menu change event listener
            document.getElementById('length-menu').addEventListener('change', function() {
                if (this.value === 'all') {
                    rowsPerPage = filteredEmployees.length; // Show all items
                } else {
                    rowsPerPage = parseInt(this.value); // Show selected number of items per page
                }
                currentPage = 1; // Reset to first page when length changes
                displayTable();
            });
            // Previous page button click event handler
            document.getElementById('prev-page').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    displayTable();
                }
            });
            // Next page button click event handler
            document.getElementById('next-page').addEventListener('click', function() {
                if (currentPage < Math.ceil(filteredEmployees.length / rowsPerPage)) {
                    currentPage++;
                    displayTable();
                }
            });
            // Page length selection change event handler
            document.getElementById('page-length').addEventListener('change', function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1; // Reset to first page
                displayTable();
            });
        });
        function fetchEmployees() {
            showLoading();
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_employees.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    employees = JSON.parse(xhr.responseText);
                    filteredEmployees = employees;
                    displayTable();
                    hideLoading();
                } else {
                    showError('Error fetching data. Please try again later.');
                }
            };
            xhr.onerror = function() {
                showError('Error fetching data. Please try again later.');
            };
            xhr.send();
        }
        function displayTable() {
            const tableBody = document.getElementById('employees-table-body');
            tableBody.innerHTML = '';
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedItems = filteredEmployees.slice(start, end);
            paginatedItems.forEach(function(employee) {
                const row = `
                <tr class="bg-white dark:bg-gray-800 dark:border-gray-200">
                    <td class="py-3 px-6">${highlightSearch(employee.first_name + ' ' + employee.last_name)}</td>
                    <td class="py-3 px-6">${highlightSearch(employee.email)}</td>
                    <td class="py-3 px-6">${highlightSearch(employee.phone)}</td>
                    <td class="py-3 px-6">${highlightSearch(employee.address)}</td>
                    <td class="py-3 px-6">${highlightSearch(employee.position)}</td>
                    <td class="py-3 px-6">${highlightSearch(employee.department)}</td>
                    <td class="py-3 px-6">${highlightSearch(employee.hire_date)}</td>
                    <td class="py-3 px-6">${highlightSearch(employee.salary)}</td>
                    <td class="py-3 px-6">
                        <a href="edit_employee.php?id=${employee.id}&name=${employee.first_name} ${employee.last_name}" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg py-1 px-3 mr-2">Edit</a>
                        <a class="bg-red-500 hover:bg-red-600 text-white rounded-lg py-1 px-3">Delete</a>
                    </td>
                </tr>
            `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
            document.getElementById('page-info').textContent = `Page ${currentPage} of ${Math.ceil(filteredEmployees.length / rowsPerPage)}`;
        }
        function highlightSearch(text) {
            const searchTerm = document.getElementById('search').value.toLowerCase();
            if (searchTerm.length === 0) {
                return text;
            }
            const regex = new RegExp(searchTerm, 'gi');
            return text.replace(regex, match => `<span class="bg-yellow-200 rounded px-1">${match}</span>`);
        }
        function filterEmployees(searchTerm) {
            showLoading();
            filteredEmployees = employees.filter(function(employee) {
                return Object.values(employee).some(function(value) {
                    return String(value).toLowerCase().includes(searchTerm);
                });
            });
            currentPage = 1;
            displayTable();
            hideLoading();
        }
        function exportTableToCSV(filename) {
            const csv = [];
            const rows = document.querySelectorAll('table tr');
            rows.forEach(function(row) {
                const rowData = Array.from(row.children).map(function(cell) {
                    return cell.textContent;
                }).join(',');
                csv.push(rowData);
            });
            const csvFile = new Blob([csv.join('\n')], {
                type: 'text/csv'
            });
            const downloadLink = document.createElement('a');
            downloadLink.download = filename;
            downloadLink.href = URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
        function showLoading() {
            document.getElementById('loading').classList.remove('hidden');
        }
        function hideLoading() {
            document.getElementById('loading').classList.add('hidden');
        }
        function showError(message) {
            const errorElement = document.getElementById('error-message');
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
            setTimeout(function() {
                errorElement.classList.add('hidden');
            }, 3000);
        }
    </script>
    <?php include('layouts/footer.php') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="theme.js"></script>
</body>
</html>