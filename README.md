# Human Resource Management System

## Overview

The Human Resource Management System (HRMS) is a comprehensive web application designed to facilitate the efficient management of HR tasks. It includes functionalities for managing employee data, recruitment processes, task assignments, and more, making it an essential tool for any organization.

## Features

### Employee Management
- **Add, Edit, Delete Employee Details:** Maintain comprehensive records of all employees.
- **Employee Directory:** Easily searchable database of all current employees.
- **Role Management:** Define and manage roles and permissions for different users.

### Recruitment
- **Job Listings:** Post and manage job openings.
- **Application Management:** Track and manage job applications from prospective employees.
- **Interview Scheduling:** Schedule and track interviews with applicants.

### Authentication
- **Secure Login:** User authentication using secure methods.
- **Session Management:** Maintain secure user sessions.

### Dashboard
- **HR Metrics:** Overview of key HR metrics and performance indicators.
- **Notifications:** Alerts and reminders for important HR tasks.

### Task Management
- **Create and Assign Tasks:** Assign tasks to employees and track their progress.
- **Task Overview:** Dashboard for viewing all tasks and their statuses.

## Technologies Used

- **Frontend:** HTML, CSS, JavaScript, Bootstrap for responsive design.
- **Backend:** PHP for server-side scripting.
- **Database:** MySQL for data storage and retrieval.
- **APIs:** Integration with Google OAuth for authentication and Spotify API for additional functionalities.

## Installation

To set up the HRMS on your local machine, follow these steps:

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/enisgjinii/Human-Resource-Management-System.git
   ```
2. **Navigate to the Project Directory:**
   ```bash
   cd Human-Resource-Management-System
   ```
3. **Database Setup:**
   - Create a MySQL database.
   - Import the provided SQL file (`database.sql`) to set up the necessary tables.
   - Update the `config.php` file with your database credentials.

4. **Run the Application:**
   - Use a local server (like XAMPP or WAMP) to run the application.
   - Open the application in your web browser.

## Usage

- **Dashboard:** Access the main dashboard for an overview of HR activities.
- **Employee Management:** Navigate to the employee management section to add, edit, or remove employee details.
- **Recruitment:** Manage job postings and applications through the recruitment section.
- **Task Management:** Assign tasks to employees and monitor their completion.

## Contributing

We welcome contributions to improve the HRMS. To contribute:

1. **Fork the Repository:** Click the fork button at the top right of the repository page.
2. **Create a New Branch:**
   ```bash
   git checkout -b feature-branch
   ```
3. **Make Changes:** Implement your changes in the new branch.
4. **Commit Your Changes:**
   ```bash
   git commit -m 'Add feature'
   ```
5. **Push to the Branch:**
   ```bash
   git push origin feature-branch
   ```
6. **Open a Pull Request:** Go to the original repository and open a pull request to merge your changes.

## License

This project is licensed under the MIT License. For more details, see the [LICENSE](LICENSE) file.

## Contact

For any inquiries or support, please contact the project maintainer, [Enis Gjini](https://github.com/enisgjinii).
