<?php
// Include authentication check or any necessary initializations
include('check_auth.php');

// Include the Google Client Library and configuration
require_once 'vendor/autoload.php';
$config = require 'config.php'; // Adjust this to match your configuration file

// Initialize Google Client
$client = new Google_Client();
$client->setClientId($config['clientID']);
$client->setClientSecret($config['clientSecret']);
$client->setRedirectUri($config['redirectUri']);
$client->setAccessType('offline'); // Ensure offline access to get a refresh token
$client->setIncludeGrantedScopes(true); // Incremental auth
$client->addScope("https://www.googleapis.com/auth/calendar");

// Set secure cookie options
$cookieOptions = [
    'expires' => time() + (86400 * 30), // 30 days
    'path' => '/',
    'domain' => '', // Adjust to your domain
    'secure' => true, // Only send over HTTPS
    'httponly' => true, // Accessible only through the HTTP protocol
    'samesite' => 'Strict' // CSRF protection
];

// Check if the refresh token exists in cookies
if (isset($_COOKIE['refresh_token'])) {
    // Set the refresh token
    $client->fetchAccessTokenWithRefreshToken($_COOKIE['refresh_token']);
} else {
    // Redirect to authorization if refresh token is not found
    $authUrl = htmlspecialchars($client->createAuthUrl(), ENT_QUOTES, 'UTF-8');
    echo "<a href='$authUrl'>Authorize with Google</a>";
    exit;
}

// Create Google Calendar service
$calendarService = new Google\Service\Calendar($client);

// Fetch calendars
$calendarList = $calendarService->calendarList->listCalendarList();
$events = [];
foreach ($calendarList->getItems() as $calendar) {
    $calendarId = htmlspecialchars($calendar->getId(), ENT_QUOTES, 'UTF-8');
    $eventsResult = $calendarService->events->listEvents($calendarId);
    foreach ($eventsResult->getItems() as $event) {
        $events[] = [
            'title' => htmlspecialchars($event->getSummary(), ENT_QUOTES, 'UTF-8'),
            'start' => htmlspecialchars($event->getStart()->getDateTime() ?: $event->getStart()->getDate(), ENT_QUOTES, 'UTF-8'),
            'end' => htmlspecialchars($event->getEnd()->getDateTime() ?: $event->getEnd()->getDate(), ENT_QUOTES, 'UTF-8'),
            'calendar' => $calendarId
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentFlow</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <script>
        // Function to determine if the current time is between 19:00 and 06:00
        function isNightTime() {
            const now = new Date();
            const hour = now.getHours();
            return (hour >= 19 || hour < 6);
        }

        // Function to apply the dark theme based on conditions
        function applyTheme() {
            if (isNightTime() || localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Initial application of the theme
        applyTheme();

        // Listen for changes in system's color scheme preference
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', applyTheme);

        // Listen for changes in local storage for color theme
        window.addEventListener('storage', (event) => {
            if (event.key === 'color-theme') {
                applyTheme();
            }
        });
    </script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                themeSystem: 'bootstrap',
                events: <?php echo json_encode($events); ?>,
                editable: true,
                eventClick: ({
                    event
                }) => {
                    Swal.fire({
                        title: 'Event',
                        text: 'Event: ' + event.title,
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                },
                dateClick: ({
                    dateStr
                }) => {
                    Swal.fire({
                        title: 'Enter Event Title',
                        input: 'text',
                        showCancelButton: true,
                        confirmButtonText: 'Add Event',
                        cancelButtonText: 'Cancel',
                        preConfirm: (title) => {
                            if (title) {
                                calendar.addEvent({
                                    title: title,
                                    start: dateStr,
                                    allDay: true
                                });
                            }
                        }
                    });
                }
            });
            calendar.render();
        });
    </script>

</head>

<body>
    <?php include('layouts/sidebar.php') ?>
    <div class="py-12 px-5 sm:ml-64 mt-14 bg-white dark:bg-gray-800">
        <div class="border border-black dark:border-gray-600 p-8 rounded-xl shadow mt-2 bg-white dark:bg-gray-800 dark:text-white">
            <div class="grid grid-cols-3 xl:grid-cols-3 xl:gap-4">
                <div class="col-span-3 xl:col-span-2 me-5">
                    <div id="calendar"></div>
                </div>
                <!-- <div class="col-span-1 xl:col-span-1">
                    <ol class="relative border-s border-gray-200 dark:border-gray-700">
                        <?php
                        // Replace 'YOUR_TODOIST_API_TOKEN' with your actual Todoist API token
                        $token = ('1396020f30fad03ba3db59edc4b51daa6067916d');
                        $url = 'https://api.todoist.com/rest/v2/tasks';
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($ch);
                        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);

                        if ($http_status == 200) {
                            $tasks = json_decode($response, true);
                            foreach ($tasks as $task) {
                                $dueDate = htmlspecialchars(date('M j, Y \a\t g:i A', strtotime($task['due']['datetime'])), ENT_QUOTES, 'UTF-8');
                                $content = htmlspecialchars($task['content'], ENT_QUOTES, 'UTF-8');
                                $description = htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8');
                        ?>
                                <li class="mb-10 ms-4">
                                    <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                    <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">
                                        <?php echo $dueDate; ?>
                                    </time>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo $content; ?></h3>
                                    <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400"><?php echo $description; ?></p>
                                </li>
                        <?php
                            }
                        }
                        ?>
                    </ol>
                </div> -->
            </div>
        </div>
        <!-- <?php include('layouts/footer.php') ?> -->
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>