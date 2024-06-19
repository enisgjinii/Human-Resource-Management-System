<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentFlow</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>
</head>

<body>
    <?php include('layouts/sidebar.php') ?>
    <div class="p-4 sm:ml-64 dark:bg-gray-800 ">
        <div id="timeline" class="mt-10"></div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="theme.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var timelineEl = document.getElementById('timeline');

            // Function to fetch events
            function fetchEvents() {
                return fetch('spotify.php') // Adjust endpoint according to your setup
                    .then(response => response.json())
                    .then(data => {
                        // Ensure data is in the correct format
                        if (!Array.isArray(data)) {
                            data = [data];
                        }

                        // Create an array of events from the list of songs
                        return data.map((song, index) => ({
                            id: index,
                            content: `${song.song} <br> <small>${song.artist} - ${song.album}</small>`,
                            start: new Date(song.timestamp), // Use timestamp from the song data
                            end: new Date(song.timestamp + song.duration_ms), // Calculate end time
                            title: 'Artist: ' + song.artist + '\nAlbum: ' + song.album,
                            group: song.artist
                        }));
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        return [];
                    });
            }

            // Create a timeline
            var container = document.getElementById('timeline');
            var timeline;

            fetchEvents().then(events => {
                var items = new vis.DataSet(events);

                var options = {
                    stack: false,
                    showCurrentTime: true,
                    zoomMin: 1000 * 60 * 60, // 1 hour
                    zoomMax: 1000 * 60 * 60 * 24 * 30 // 1 month
                };

                timeline = new vis.Timeline(container, items, options);
            });

            // Function to check the currently playing song and update the timeline if it changes
            let currentSong = '';
            let currentSongTimestamp = 0;

            function checkCurrentSong() {
                fetch('spotify.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.song) {
                            if (currentSong !== data.song || currentSongTimestamp !== data.timestamp) {
                                currentSong = data.song;
                                currentSongTimestamp = data.timestamp;
                                fetchEvents().then(events => {
                                    var items = new vis.DataSet(events);
                                    timeline.setItems(items); // Refresh the timeline
                                });
                            }
                        } else {
                            console.error('Unexpected data format or no data:', data);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching current song:', error);
                    });
            }

            // Long polling for updates
            function longPoll() {
                checkCurrentSong();
                setTimeout(longPoll, 1000); // Poll every 10 seconds
            }

            // Initial check for the current song
            longPoll();
        });
    </script>
</body>

</html>