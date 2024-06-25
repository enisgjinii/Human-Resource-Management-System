<?php
include('check_auth.php');
require_once 'vendor/autoload.php';
$config = require 'config.php';

$client = new Google_Client();
$client->setClientId($config['clientID']);
$client->setClientSecret($config['clientSecret']);
$client->setRedirectUri($config['redirectUri']);
$client->setAccessType('offline');
$client->setIncludeGrantedScopes(true);
$client->addScope("https://www.googleapis.com/auth/calendar");

if (isset($_COOKIE['refresh_token'])) {
    $client->fetchAccessTokenWithRefreshToken($_COOKIE['refresh_token']);
} else {
    echo json_encode([]);
    exit;
}

$calendarService = new Google\Service\Calendar($client);

try {
    $calendarList = $calendarService->calendarList->listCalendarList();
    $calendars = $calendarList->getItems();
    $events = [];
    foreach ($calendars as $calendar) {
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
    echo json_encode($events);
} catch (Exception $e) {
    echo json_encode([]);
}
