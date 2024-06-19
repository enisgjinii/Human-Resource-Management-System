<?php
// Include authentication check
include('check_auth.php');
// Include vendor
require_once 'vendor/autoload.php';
// Ensure we have a refresh token from the cookie
$refresh_token = isset($_COOKIE['refresh_token']) ? $_COOKIE['refresh_token'] : null;
if ($refresh_token) {
  // Use prepared statement to fetch user information securely
  $stmt = $conn->prepare("SELECT id, access_token, refresh_token, name, surname, email, profile_picture_url, stripe_customer_id FROM users WHERE refresh_token = ?");
  if (!$stmt) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
  }
  $stmt->bind_param('s', $refresh_token);
  $stmt->execute();
  $result = $stmt->get_result();
  if (!$result) {
    die('Execute failed: ' . htmlspecialchars($stmt->error));
  }
  if ($result->num_rows > 0) {
    // Fetch user information
    $user_infos = $result->fetch_assoc();
    $user_id = $user_infos['id'];
    $access_token = $user_infos['access_token'];
    $refresh_token = $user_infos['refresh_token'];
    $name = $user_infos['name'];
    $surname = $user_infos['surname'];
    $email = $user_infos['email'];
    $profile_picture_url = $user_infos['profile_picture_url'];
    $stripe_customer_id = $user_infos['stripe_customer_id'];
    // Fetch Stripe customer and related data
    require_once 'secrets.php'; // Ensure this file includes $stripeSecretKey
    \Stripe\Stripe::setApiKey($stripeSecretKey);
    try {
      // Fetch customer object from Stripe using Customer ID
      $customer = \Stripe\Customer::retrieve($stripe_customer_id);
      // Fetch subscriptions associated with the customer
      $subscriptions = \Stripe\Subscription::all(['customer' => $stripe_customer_id]);
      // Fetch payment methods associated with the customer
      $paymentMethods = \Stripe\PaymentMethod::all([
        'customer' => $stripe_customer_id,
        'type' => 'card',
      ]);
    } catch (Exception $e) {
      echo '<p class="text-red-500">Stripe Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
      exit;
    }
  } else {
    echo "No user found with the provided refresh token.";
  }
  // Close statement and result set
  $stmt->close();
  $result->free();
} else {
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
              <img class="w-10 h-10 rounded-full" src="<?php echo $profile_picture_url; ?>" alt="User">
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
              <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300 " role="none">
                <?php
                // Check if $subscriptions->data is not empty
                if (!empty($subscriptions->data)) {
                  // Get the status of the first subscription if available
                  $status = $subscriptions->data[0]->status ?? null;
                  // Determine the class and text for the status badge
                  $class = $status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                  $text = $status === 'active' ? 'Paided' : 'Free';
                ?>
                  <span class="inline-flex items-center px-3 py-0.5 rounded-lg text-sm font-medium mt-2 <?php echo $class; ?>">
                    <?php echo $text; ?>
                  </span>
                <?php
                }
                ?>
              </p>
            </div>
            <ul class="py-1" role="none">
              <li>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Dashboard</a>
              </li>
              <li>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Settings</a>
              </li>
              <?php
              $status = $subscriptions->data[0]->status ?? null;
              if ($status === 'active') {
              ?>
                <li>
                  <a href="create-portal-session.php?customer_id=<?php echo $stripe_customer_id; ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Manage billing</a>
                </li>
              <?php
              }
              ?>
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
    <ul class="space-y-2 font-medium text-sm">
      <?php
      $menuItems = [
        ['url' => 'dashboard.php', 'icon' => 'fi fi-rr-home', 'label' => 'Dashboard'],
        ['url' => 'employees.php', 'icon' => 'fi fi-rr-users', 'label' => 'Employees', 'status' => 'Pro'],
        ['url' => 'calendar.php', 'icon' => 'fi fi-rr-calendar-lines', 'label' => 'Calendar'],
        ['url' => 'tasks.php', 'icon' => 'fi fi-rr-list-check', 'label' => 'Tasks', 'notificationCount' => 3],
        ['url' => 'employee_directory.php', 'icon' => 'fi fi-rr-address-book', 'label' => 'Employee Directory'],
        ['url' => 'recruitment.php', 'icon' => 'fi fi-rr-briefcase', 'label' => 'Recruitment & Onboarding'],
        ['url' => 'performance.php', 'icon' => 'fi fi-rr-star', 'label' => 'Performance Management'],
        ['url' => 'leave_management.php', 'icon' => 'fi fi-rr-calendar-check', 'label' => 'Leave Management'],
        ['url' => 'benefits.php', 'icon' => 'fi fi-rr-dollar', 'label' => 'Benefits Administration'],
        ['url' => 'training.php', 'icon' => 'fi fi-rr-book', 'label' => 'Training & Development'],
        ['url' => 'payroll.php', 'icon' => 'fi fi-rr-money', 'label' => 'Payroll & Compensation'],
        ['url' => 'compliance.php', 'icon' => 'fi fi-rr-memo-circle-check', 'label' => 'Compliance & Policies'],
        ['url' => 'analytics.php', 'icon' => 'fi fi-rr-chart-simple', 'label' => 'Analytics & Reporting'],
        ['url' => 'invoices.php', 'icon' => 'fi fi-rr-receipt', 'label' => 'Invoices'],
        ['url' => 'settings.php', 'icon' => 'fi fi-rr-settings', 'label' => 'Settings'],
      ];
      foreach ($menuItems as $item) {
        $linkClass = ($current_page == $item['url']) ? $link_class : '';
        echo '<li>';
        echo '<a href="' . htmlspecialchars($item['url']) . '" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group text-sm' . $linkClass . '">';
        echo '<i class="' . htmlspecialchars($item['icon']) . '"></i>';
        echo '<span class="flex-1 ms-3 whitespace-nowrap">' . htmlspecialchars($item['label']) . '</span>';
        if (isset($item['status'])) {
          echo '<span class="inline-flex items-center justify-center px-2 ms-3 font-medium text-gray-800 bg-red-100 rounded-full dark:bg-red-500 dark:text-white">' . htmlspecialchars($item['status']) . '</span>';
        }
        if (isset($item['notificationCount'])) {
          echo '<span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 font-small text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">' . htmlspecialchars($item['notificationCount']) . '</span>';
        }
        echo '</a>';
        echo '</li>';
      }
      ?>
    </ul>
    <label class="inline-flex items-center mb-5 ms-2 mt-2 cursor-pointer">
      <input type="checkbox" id="theme-switch" class="sr-only peer">
      <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
    </label>
  </div>
</aside>
<script>
  var themeSwitch = document.getElementById('theme-switch');
  // Initialize the switch state based on previous settings
  if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
    themeSwitch.checked = true;
  } else {
    document.documentElement.classList.remove('dark');
    themeSwitch.checked = false;
  }
  themeSwitch.addEventListener('change', function() {
    if (themeSwitch.checked) {
      document.documentElement.classList.add('dark');
      localStorage.setItem('color-theme', 'dark');
    } else {
      document.documentElement.classList.remove('dark');
      localStorage.setItem('color-theme', 'light');
    }
  });
</script>