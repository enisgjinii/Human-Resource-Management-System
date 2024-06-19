# Human Resource Management System

Welcome to the **Human Resource Management System** repository! This project is designed to help manage human resources effectively and efficiently within an organization. It provides robust tools and functionalities for managing employee data, tracking attendance, handling payroll, and more.

## Table of Contents

- [About](#about)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Usage](#usage)
- [Folder Structure](#folder-structure)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

## About

This Human Resource Management System (HRMS) is a comprehensive solution for managing various aspects of human resources. Its built using HTML, Hack, PHP, and JavaScript. The system is designed to streamline HR processes and improve the efficiency of managing employee-related functions.

## Features

- **Employee Management**: Add, edit, and delete employee information, including personal details, contact information, and job details.
- **Attendance Tracking**: Monitor employee attendance using a calendar plugin and generate attendance reports.
- **Payroll Management**: Calculate and manage employee salaries, generate payslips, and handle other payroll-related tasks.
- **Authentication**: Secure login and logout functionality, with support for Google OAuth and Spotify authentication.
- **Dashboard**: Centralized dashboard for quick access to HR metrics and data, customizable to display important information at a glance.
- **Calendar**: Integrated calendar for managing events, schedules, and tracking employee availability.
- **Task Management**: Create, assign, and track tasks for employees, with the ability to view task status and mark tasks as completed.
- **Role-Based Access Control**: Different access levels for HR, managers, and employees to ensure data security and appropriate access.

## Technologies Used

- **PHP**: For server-side scripting.
- **HTML/CSS**: For structuring and styling the web pages.
- **JavaScript**: For client-side scripting and enhancing user interactions.
- **Hack**: A programming language for HHVM that interoperates seamlessly with PHP.
- **MySQL**: For database management.
- **Composer**: For dependency management.
- **Stripe**: For payment processing.
- **Google OAuth**: For secure employee login authentication.

## Installation

To set up the project locally, follow these steps:

1. **Clone the repository:**
    ```bash
    git clone https://github.com/enisgjinii/Human-Resource-Management-System.git
    ```
2. **Navigate to the project directory:**
    ```bash
    cd Human-Resource-Management-System
    ```
3. **Install dependencies:**
    Ensure you have PHP and a web server like Apache installed. You may need to configure your web server to serve the project files.
    ```bash
    composer install
    ```
4. **Configure the database:**
    Update the `config.php` file with your database credentials.
    ```php
    // config.php
    define('DB_SERVER', 'your_server');
    define('DB_USERNAME', 'your_username');
    define('DB_PASSWORD', 'your_password');
    define('DB_DATABASE', 'your_database');
    ```
5. **Set up authentication services:**
    Update the `google_oauth.php` and `spotify.php` files with your OAuth credentials.
    ```php
    // google_oauth.php
    $client_id = 'your_google_client_id';
    $client_secret = 'your_google_client_secret';
    ```

    ```php
    // spotify.php
    $client_id = 'your_spotify_client_id';
    $client_secret = 'your_spotify_client_secret';
    ```
6. **Run the application:**
    Start your web server and navigate to the project directory in your browser.

## Usage

1. **Login**: Access the login page to enter your credentials and access the system.
2. **Dashboard**: Use the dashboard to get an overview of HR data and metrics.
3. **Employees**: Manage employee data by adding, editing, or deleting records.
4. **Attendance**: Track employee attendance and generate attendance reports.
5. **Payroll**: Manage payroll processing and employee salary information.
6. **Tasks**: Create and assign tasks, and track their completion status.

## Folder Structure

Here is an overview of the main folders and files in the repository:

- **api/**: Contains API-related files.
- **images/**: Stores image assets.
- **layouts/**: Contains layout files for the application.
- **pages/**: Includes different web pages of the application.
- **playground/**: A space for testing and development.
- **.gitignore**: Specifies files to be ignored by Git.
- **_index.html**: The main HTML file.
- **calendar-plugin.php**: Plugin for calendar functionality.
- **config.php**
