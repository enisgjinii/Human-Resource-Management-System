<?php
// Database configuration
include("connection.php");
// Query to fetch employees
$sql = "SELECT * FROM employees";
$result = $conn->query($sql);
// Initialize an array to hold employee data
$employees = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}
// Close connection
$conn->close();
?>
<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    First Name and Last Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Email
                </th>
                <th scope="col" class="px-6 py-3">
                    Phone
                </th>
                <th scope="col" class="px-6 py-3">
                    Address
                </th>
                <th scope="col" class="px-6 py-3">
                    Date of Birth
                </th>
                <th scope="col" class="px-6 py-3">
                    Position
                </th>
                <th scope="col" class="px-6 py-3">
                    Department
                </th>
                <th scope="col" class="px-6 py-3">
                    Hire Date
                </th>
                <th scope="col" class="px-6 py-3">
                    Salary
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee) : ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                        <?php echo $employee['first_name'] . " " . $employee['last_name'];  ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                        <?php echo $employee['email']; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                        <?php echo $employee['phone']; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                        <?php echo $employee['address']; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                        <?php echo $employee['dob']; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                        <?php echo $employee['position']; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                        <?php echo $employee['department']; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                        <?php echo $employee['hire_date']; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                        <?php echo $employee['salary']; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                        <!-- Modal toggle -->
                        <button data-modal-target="employee-modal-edit<?php echo $employee['id']; ?>" data-modal-toggle="employee-modal-edit<?php echo $employee['id']; ?>" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                            Edit
                        </button>
                        <br>
                        <a href="delete_employee.php?id=<?php echo $employee['id']; ?>" type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Red</a>
                    </td>
                </tr>
                <!-- Main modal -->
                <div id="employee-modal-edit<?php echo $employee['id']; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Edit Employee
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="employee-modal-edit<?php echo $employee['id']; ?>">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <form id="editEmployeeForm<?php echo $employee['id']; ?>">
                                <input type="hidden" name="employee_id" value="<?php echo $employee['id']; ?>">
                                <div class="grid gap-4 grid-cols-2 px-4 md:p-5 space-y-4">
                                    <div>
                                        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First Name</label>
                                        <input type="text" id="first_name" name="first_name" value="<?php echo $employee['first_name']; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                    </div>
                                    <div>
                                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last Name</label>
                                        <input type="text" id="last_name" name="last_name" value="<?php echo $employee['last_name']; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                    </div>
                                    <div>
                                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                        <input type="email" id="email" name="email" value="<?php echo $employee['email']; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                    </div>
                                    <div>
                                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone</label>
                                        <input type="tel" id="phone" name="phone" value="<?php echo $employee['phone']; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                    </div>
                                    <div>
                                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                                        <input type="text" id="address" name="address" value="<?php echo $employee['address']; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                    </div>
                                    <div>
                                        <label for="dob" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date of Birth</label>
                                        <input type="date" id="dob" name="dob" value="<?php echo $employee['dob']; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                    </div>
                                    <div>
                                        <label for="position" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Position</label>
                                        <input type="text" id="position" name="position" value="<?php echo $employee['position']; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                    </div>
                                    <div>
                                        <label for="department" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department</label>
                                        <input type="text" id="department" name="department" value="<?php echo $employee['department']; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                    </div>
                                    <div>
                                        <label for="hire_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hire Date</label>
                                        <input type="date" id="hire_date" name="hire_date" value="<?php echo $employee['hire_date']; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                    </div>
                                    <div>
                                        <label for="salary" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Salary</label>
                                        <input type="text" id="salary" name="salary" value="<?php echo $employee['salary']; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <!-- Modal footer -->
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" onclick="updateEmployee(<?php echo $employee['id']; ?>)">
                                    Save Changes
                                </button>
                                <!-- Modal footer (continued) -->
                                <button type="button" class="text-gray-900 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white focus:outline-none" data-modal-hide="default-modal">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>