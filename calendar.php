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
// Fetch calendars and events
try {
    $calendarList = $calendarService->calendarList->listCalendarList();
    $calendars = $calendarList->getItems(); // Get array of calendar items
    $events = [];
    $calendarEventCounts = []; // Array to store the number of events per calendar
    foreach ($calendars as $calendar) {
        $calendarId = htmlspecialchars($calendar->getId(), ENT_QUOTES, 'UTF-8');
        $eventsResult = $calendarService->events->listEvents($calendarId);
        $eventCount = count($eventsResult->getItems()); // Count the number of events
        $calendarEventCounts[$calendarId] = $eventCount; // Store the event count
        foreach ($eventsResult->getItems() as $event) {
            $events[] = [
                'title' => htmlspecialchars($event->getSummary(), ENT_QUOTES, 'UTF-8'),
                'start' => htmlspecialchars($event->getStart()->getDateTime() ?: $event->getStart()->getDate(), ENT_QUOTES, 'UTF-8'),
                'end' => htmlspecialchars($event->getEnd()->getDateTime() ?: $event->getEnd()->getDate(), ENT_QUOTES, 'UTF-8'),
                'calendar' => $calendarId
            ];
        }
    }
} catch (Exception $e) {
    echo 'Error fetching calendars: ' . $e->getMessage();
    $calendars = [];
    $events = [];
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
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <script src='theme.js'></script>
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
            const checkboxes = document.querySelectorAll('.calendar-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    const selectedCalendars = Array.from(checkboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);
                    const filteredEvents = <?php echo json_encode($events); ?>.filter(event => selectedCalendars.includes(event.calendar));
                    calendar.removeAllEvents();
                    calendar.addEventSource(filteredEvents);
                });
            });
        });
    </script>
</head>

<body>
    <!-- Include the sidebar layout -->
    <?php include('layouts/sidebar.php') ?>
    <div class="py-12 px-5 sm:ml-64 mt-10 bg-white dark:bg-gray-800">
        <!-- Border and shadow styling for the content box -->
        <div class="border border-black dark:border-gray-600 px-8 pt-5 rounded-xl bg-white dark:bg-gray-800 dark:text-white">
            <!-- Grid layout for responsive design -->
            <div class="grid grid-cols-3 xl:grid-cols-3 xl:gap-4">
                <!-- Main content area spanning 2 columns on extra-large screens -->
                <div class="col-span-3 xl:col-span-2 me-5">
                    <!-- Container for displaying calendars -->
                    <?php
                    if (!empty($subscriptions->data)) {
                        $status = $subscriptions->data[0]->status ?? null;
                        if ($status === 'active') {
                            $class = 'bg-green-100 text-green-800';
                            $text = 'Paided';
                    ?>
                            <span class="inline-flex items-center px-3 py-0.5 mb-2 rounded-lg text-sm font-medium <?= $class; ?>">
                                <?= $text; ?>
                            </span>
                            <div class="mb-4">
                                <h1 class="text-2xl font-semibold">Calendars</h1>
                                <p class="text-gray-500 dark:text-gray-400 my-2">Select calendars to display events</p>
                                <?php
                                if (!empty($calendars)) {
                                    echo '<div class="flex flex-wrap gap-4">';
                                    foreach ($calendars as $calendar) {
                                        $calendarId = htmlspecialchars($calendar->getId(), ENT_QUOTES, 'UTF-8');
                                        $calendarSummary = htmlspecialchars($calendar->getSummary(), ENT_QUOTES, 'UTF-8');
                                        $calendarBackgroundColor = htmlspecialchars($calendar->getBackgroundColor(), ENT_QUOTES, 'UTF-8');
                                ?>
                                        <div class='p-2 border border-<?= $calendarBackgroundColor ?>-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 flex items-center' style='border-left: 4px solid <?= $calendarBackgroundColor ?>;'>
                                            <input type='checkbox' id='calendar_<?= $calendarId ?>' class='calendar-checkbox w-4 h-4 text-<?= $calendarBackgroundColor ?>-600 bg-<?= $calendarBackgroundColor ?>-100 border-<?= $calendarBackgroundColor ?>-300 rounded focus:ring-<?= $calendarBackgroundColor ?>-500 dark:focus:ring-<?= $calendarBackgroundColor ?>-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-<?= $calendarBackgroundColor ?>-600' value='<?= $calendarId ?>' checked>
                                            <label for='calendar_<?= $calendarId ?>' class='ms-2 text-sm font-medium text-gray-900 dark:text-gray-300'><?= $calendarSummary ?></label>
                                        </div>
                                <?php
                                    }
                                    echo '</div>';
                                } else {
                                    echo "<p class='text-gray-900 dark:text-white'>No calendars found.</p>";
                                }
                                ?>
                            </div>
                            <!-- Placeholder for the calendar widget -->
                            <div id="calendar"></div>
                        <?php
                        }
                    } else { ?>
                        <section class="bg-white dark:bg-gray-800 h-screen">
                            <div class="py-8 px-4 mx-auto max-w-screen-xl  lg:py-16 lg:px-6">
                                <div class="mx-auto max-w-screen-md text-center mb-8 lg:mb-12">
                                    <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Designed for persons like your</h2>
                                    <p class="mb-5 font-light text-gray-500 sm:text-xl dark:text-gray-400">Here at TalentFlow we focus on markets where technology, innovation, and capital can unlock long-term value and drive economic growth.</p>
                                </div>
                                <div class="space-y-8 sm:gap-6 xl:gap-10 lg:space-y-0">
                                    <!-- Pricing Card -->
                                    <div class="flex flex-col p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                                        <h3 class="mb-4 text-2xl font-semibold">Starter</h3>
                                        <p class="font-light text-gray-500 sm:text-lg dark:text-gray-400">Best option for personal use & for your next level of productivity.</p>
                                        <div class="flex justify-center items-baseline my-8">
                                            <span class="mr-2 text-5xl font-extrabold">$9</span>
                                            <span class="text-gray-500 dark:text-gray-400">/month</span>
                                        </div>
                                        <!-- List -->
                                        <ul role="list" class="mb-8 space-y-4 text-left">
                                            <li class="flex items-center space-x-3">
                                                <!-- Icon -->
                                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Individual configuration</span>
                                            </li>
                                            <li class="flex items-center space-x-3">
                                                <!-- Icon -->
                                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>No setup, or hidden fees</span>
                                            </li>
                                            <li class="flex items-center space-x-3">
                                                <!-- Icon -->
                                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Team size: <span class="font-semibold">1 developer</span></span>
                                            </li>
                                            <li class="flex items-center space-x-3">
                                                <!-- Icon -->
                                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Premium support: <span class="font-semibold">6 months</span></span>
                                            </li>
                                            <li class="flex items-center space-x-3">
                                                <!-- Icon -->
                                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Free updates: <span class="font-semibold">6 months</span></span>
                                            </li>
                                        </ul>
                                        <form action="create-checkout-session.php" method="POST">
                                            <input type="hidden" name="lookup_key" value="TalentFlow-78d1dfd" />
                                            <button type="submit" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-white  dark:focus:ring-primary-900">Go to checkout</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </section>
                    <?php  }
                    ?>
                </div>
            </div>
        </div>
        <!-- Include the footer layout (commented out) -->
        <!-- <?php include('layouts/footer.php') ?> -->
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="theme.js"></script>
</body>

</html>