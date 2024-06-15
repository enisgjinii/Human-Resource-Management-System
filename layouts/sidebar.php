<?php include('check_auth.php');
// Get refresh token from cookie securely
$refresh_token = isset($_COOKIE['refresh_token']) ? $_COOKIE['refresh_token'] : null;
// Check if the refresh token exists
if ($refresh_token) {
  // Use a prepared statement to prevent SQL injection
  $stmt = $conn->prepare("SELECT id, access_token, refresh_token, name, surname, email, profile_picture_url FROM users WHERE refresh_token = ?");
  $stmt->bind_param('s', $refresh_token);
  $stmt->execute();
  $result = $stmt->get_result();
  // Fetch user information
  if ($result && $result->num_rows > 0) {
    $user_infos = $result->fetch_assoc();
    $user_id = $user_infos['id'];
    $access_token = $user_infos['access_token'];
    $refresh_token = $user_infos['refresh_token'];
    $name = $user_infos['name'];
    $surname = $user_infos['surname'];
    $email = $user_infos['email'];
    $profile_picture_url = $user_infos['profile_picture_url'];
  } else {
    // Handle case where no user is found
    echo "No user found with the provided refresh token.";
  }
  // Close statement and result set
  $stmt->close();
  $result->free();
} else {
  // Handle case where refresh token is not provided
  echo "Refresh token not provided.";
}
// Close the database connection
$conn->close();
?>
<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start rtl:justify-end">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
          <span class="sr-only">Open sidebar</span>
          <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
          </svg>
        </button>
        <a href="https://flowbite.com" class="flex ms-2 md:me-24">
          <img src="images/logo.png" class="h-12 w-12 mr-3" alt="Flowbite Logo">
          <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">TalentFlow
          </span>
        </a>
      </div>
      <div class="flex items-center">
        <div class="flex items-center ms-3">
          <?php
          include 'check_auth.php';
          // Function to validate user ID
          function validateUserId($user_id)
          {
            return isset($user_id) && is_int($user_id);
          }
          // Function to get subscription status
          function getSubscriptionStatus($conn, $user_id)
          {
            $status = null;
            try {
              $stmt = $conn->prepare("SELECT subscription_status FROM users WHERE id = ?");
              $stmt->bind_param("i", $user_id);
              $stmt->execute();
              $result = $stmt->get_result();
              if ($result->num_rows === 0) {
                throw new Exception('User not found');
              }
              $row = $result->fetch_assoc();
              $status = $row['subscription_status'];
              $stmt->close();
            } catch (Exception $e) {
              error_log($e->getMessage()); // Log the error
            }
            return $status;
          }
          // Validate user ID
          if (!validateUserId($user_id)) {
            die('Invalid user ID');
          }
          // Get subscription status
          $subscription_status = getSubscriptionStatus($conn, $user_id);
          // Display upgrade button if subscription is not active
          if ($subscription_status && $subscription_status != 'active' || $subscription_status === null) {
          ?>
            <button data-modal-target="checkout-modal" data-modal-toggle="checkout-modal" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
              Upgrade to Pro ⭐
            </button>
          <?php } ?>
          <div id="checkout-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-7xl max-h-full">
              <!-- Modal content -->
              <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                  <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Checkout
                  </h3>
                  <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="checkout-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                  </button>
                </div>
                <!-- Modal body -->
                <div class="space-y-4 bg-gray-100 rounded-lg shadow-lg">
                  <section class="bg-white dark:bg-gray-900">
                    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                      <div class="mx-auto max-w-screen-md text-center mb-8 lg:mb-12">
                        <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Designed for business teams like yours</h2>
                        <p class="mb-5 font-light text-gray-500 sm:text-xl dark:text-gray-400">Here at Flowbite we focus on markets where technology, innovation, and capital can unlock long-term value and drive economic growth.</p>
                      </div>
                      <div class="space-y-8 sm:gap-6 xl:gap-10 lg:space-y-0">
                        <!-- Pricing Card -->
                        <div class="flex flex-col p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                          <h3 class="mb-4 text-2xl font-semibold">Starter</h3>
                          <p class="font-light text-gray-500 sm:text-lg dark:text-gray-400">Best option for personal use & for your next project.</p>
                          <div class="flex justify-center items-baseline my-8">
                            <span class="mr-2 text-5xl font-extrabold">€20</span>
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
                          <form action="create-checkout-session.php" method="POST" class="flex justify-center">
                            <!-- Add a hidden field with the lookup_key of your Price -->
                            <input type="hidden" name="lookup_key" value="TalentFlow-78d1dfd" />
                            <button id="checkout-and-portal-button" type="submit" class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-blue-600 transition duration-300">Checkout</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </section>
                </div>
              </div>
            </div>
          </div>
          <div>
            <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
              <span class="sr-only">Open user menu</span>
              <img class="w-8 h-8 rounded-full" src="<?php echo $profile_picture_url; ?>" alt="User">
            </button>
          </div>
          <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
            <div class="px-4 py-3" role="none">
              <p class="text-sm text-gray-900 dark:text-white" role="none">
                <?php echo $name . ' ' . $surname; ?>
              </p>
              <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                <?php echo $email; ?>
              </p>
            </div>
            <ul class="py-1" role="none">
              <li>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Dashboard</a>
              </li>
              <li>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Settings</a>
              </li>
              <li>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Earnings</a>
              </li>
              <li>
                <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Sign out</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>
<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
  <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
    <?php
    $current_page = basename($_SERVER['PHP_SELF']); // Get the current page file name
    // Define the class based on the current page
    if ($current_page == 'dashboard.php' || $current_page == 'tasks.php' || $current_page == 'calendar.php') {
      $link_class = 'bg-gray-100 dark:bg-gray-700'; // Common class for these pages
    } else {
      $link_class = ''; // Default class if none of the above
    }
    ?>
    <ul class="space-y-2 font-medium">
      <li>
        <a href="dashboard.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group <?php echo ($current_page == 'dashboard.php') ? $link_class : ''; ?>">
          <i class="fi fi-rr-home"></i>
          <span class="ms-3">Dashboard</span>
        </a>
      </li>
      <li>
        <a href="calendar.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group <?php echo ($current_page == 'calendar.php') ? $link_class : ''; ?>">
          <i class="fi fi-rr-calendar-lines"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">Calendar</span>
          <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-red-100 rounded-full dark:bg-red-500 dark:text-white">Pro</span>
        </a>
      </li>
      <li>
        <a href="employees.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group <?php echo ($current_page == 'employees.php') ? $link_class : ''; ?>">
          <i class="fi fi-rr-users"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">Employees</span>
          <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-red-100 rounded-full dark:bg-gray-700 dark:text-gray-300">Pro</span>
        </a>
      </li>
      <li>
        <a href="tasks.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group <?php echo ($current_page == 'tasks.php') ? $link_class : ''; ?>">
          <i class="fi fi-rr-list-check"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">Tasks</span>
          <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">3</span>
        </a>
      </li>
      <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fi fi-rr-receipt"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">Invoices</span>
        </a>
      </li>
      <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fi fi-rr-settings"></i>
          <span class="flex-1 ms-3 whitespace-nowrap">Settings</span>
        </a>
      </li>
      <li>
        <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
          <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
          </svg>
          <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
          </svg>
        </button>
      </li>
    </ul>
  </div>
</aside>
<script>
  var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
  var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
  var themeToggleBtn = document.getElementById('theme-toggle');
  // Apply theme based on the input parameter
  function applyTheme(theme) {
    if (theme === 'dark') {
      document.documentElement.classList.add('dark');
      themeToggleLightIcon.classList.remove('hidden');
      themeToggleDarkIcon.classList.add('hidden');
      localStorage.setItem('color-theme', 'dark');
      themeToggleBtn.setAttribute('aria-label', 'Switch to light theme');
    } else {
      document.documentElement.classList.remove('dark');
      themeToggleLightIcon.classList.add('hidden');
      themeToggleDarkIcon.classList.remove('hidden');
      localStorage.setItem('color-theme', 'light');
      themeToggleBtn.setAttribute('aria-label', 'Switch to dark theme');
    }
  }
  // Check if the current time is night time
  function isNightTime() {
    var hour = new Date().getHours();
    return hour >= 18 || hour < 6;
  }
  // Detect and apply changes in the system color scheme
  function detectSystemColorSchemeChange() {
    var darkSchemeQuery = window.matchMedia('(prefers-color-scheme: dark)');
    darkSchemeQuery.addEventListener('change', (e) => {
      if (e.matches) {
        applyTheme('dark');
      } else {
        applyTheme('light');
      }
    });
  }
  // Initialize the theme based on local storage, system preferences, or time of day
  function initializeTheme() {
    var storedTheme = localStorage.getItem('color-theme');
    if (storedTheme) {
      applyTheme(storedTheme);
    } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
      applyTheme('dark');
    } else if (window.matchMedia('(prefers-color-scheme: light)').matches) {
      applyTheme('light');
    } else if (isNightTime()) {
      applyTheme('dark');
    } else {
      applyTheme('light');
    }
  }
  // Toggle the theme when the button is clicked
  themeToggleBtn.addEventListener('click', function() {
    if (document.documentElement.classList.contains('dark')) {
      applyTheme('light');
    } else {
      applyTheme('dark');
    }
  });
  // Apply the initial theme
  initializeTheme();
  // Detect system color scheme changes
  detectSystemColorSchemeChange();
</script>