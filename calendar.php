<?php include('check_auth.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentFlow </title>
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

</head>

<body>
    <?php include('layouts/sidebar.php') ?>
    <div class="p-4 sm:ml-64 mt-14">
        <div class="grid grid-cols-3 gap-4 mb-4">
        </div>

        <?php include('layouts/footer.php') ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>