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
<nav class="fixed top-0 z-40 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700" id="navbar">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start rtl:justify-end">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
          <span class="sr-only">Open sidebar</span>
          <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
          </svg>
        </button>
        <!-- <a href="https://flowbite.com" class="flex ms-2 md:me-24">
          <img src="images/logo.png" class="h-12 w-12 mr-3" alt="Flowbite Logo">
          <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">TalentFlow
          </span>
        </a> -->
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
<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
  <h3 class="text-xl px-3 py-4 font-semibold text-gray-900 dark:text-white"> TalentFlow</h3>
  <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800 pt-2">
    <?php
    $current_page = basename($_SERVER['PHP_SELF']); // Get the current page file name
    ?>
    <ul class="space-y-2 font-medium text-sm">
      <?php
      $menuItems = [
        ['url' => 'dashboard.php', 'icon' => 'fi fi-rr-home', 'label' => 'Dashboard'],
        ['url' => 'employees.php', 'icon' => 'fi fi-rr-users', 'label' => 'Employees', 'status' => 'Pro'],
        ['url' => 'calendar.php', 'icon' => 'fi fi-rr-calendar-lines', 'label' => 'Calendar'],
        ['url' => 'tasks.php', 'icon' => 'fi fi-rr-list-check', 'label' => 'Tasks'],
        ['url' => 'recruitment.php', 'icon' => 'fi fi-rr-briefcase', 'label' => 'Recruitment & Onboarding'],
        ['url' => 'documents.php', 'icon' => 'fi fi-rr-document', 'label' => 'Documents'],
        ['url' => 'settings.php', 'icon' => 'fi fi-rr-settings', 'label' => 'Settings'],
      ];
      foreach ($menuItems as $item) {
        // Determine if this item is the current page
        $isActive = ($current_page == $item['url']);
        $linkClass = $isActive ? ' bg-gray-100 dark:bg-gray-700' : '';
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
    <ul class="pt-4 mt-4 space-y-2 font-medium text-sm border-t border-gray-200 dark:border-gray-700">
      <li>
        <a href="#" class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group">
          <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 17 20">
            <path d="M7.958 19.393a7.7 7.7 0 0 1-6.715-3.439c-2.868-4.832 0-9.376.944-10.654l.091-.122a3.286 3.286 0 0 0 .765-3.288A1 1 0 0 1 4.6.8c.133.1.313.212.525.347A10.451 10.451 0 0 1 10.6 9.3c.5-1.06.772-2.213.8-3.385a1 1 0 0 1 1.592-.758c1.636 1.205 4.638 6.081 2.019 10.441a8.177 8.177 0 0 1-7.053 3.795Z" />
          </svg>
          <span class="ms-3">Upgrade to Pro</span>
        </a>
      </li>
      <li>
        <a href="#" class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group">
          <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
            <path d="M16 14V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h12a1 1 0 0 0 0-2h-1v-2a2 2 0 0 0 2-2ZM4 2h2v12H4V2Zm8 16H3a1 1 0 0 1 0-2h9v2Z" />
          </svg>
          <span class="ms-3">Documentation</span>
        </a>
      </li>
      <li>
        <a href="#" class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group">
          <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
            <path d="M18 0H6a2 2 0 0 0-2 2h14v12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Z" />
            <path d="M14 4H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2ZM2 16v-6h12v6H2Z" />
          </svg>
          <span class="ms-3">Components</span>
        </a>
      </li>
      <li>
        <a href="#" class="flex items-center p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group">
          <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 21 21">
            <path d="m5.4 2.736 3.429 3.429A5.046 5.046 0 0 1 10.134 6c.356.01.71.06 1.056.147l3.41-3.412c.136-.133.287-.248.45-.344A9.889 9.889 0 0 0 10.269 1c-1.87-.041-3.713.44-5.322 1.392a2.3 2.3 0 0 1 .454.344Zm11.45 1.54-.126-.127a.5.5 0 0 0-.706 0l-2.932 2.932c.029.023.049.054.078.077.236.194.454.41.65.645.034.038.078.067.11.107l2.927-2.927a.5.5 0 0 0 0-.707Zm-2.931 9.81c-.024.03-.057.052-.081.082a4.963 4.963 0 0 1-.633.639c-.041.036-.072.083-.115.117l2.927 2.927a.5.5 0 0 0 .707 0l.127-.127a.5.5 0 0 0 0-.707l-2.932-2.931Zm-1.442-4.763a3.036 3.036 0 0 0-1.383-1.1l-.012-.007a2.955 2.955 0 0 0-1-.213H10a2.964 2.964 0 0 0-2.122.893c-.285.29-.509.634-.657 1.013l-.01.016a2.96 2.96 0 0 0-.21 1 2.99 2.99 0 0 0 .489 1.716c.009.014.022.026.032.04a3.04 3.04 0 0 0 1.384 1.1l.012.007c.318.129.657.2 1 .213.392.015.784-.05 1.15-.192.012-.005.02-.013.033-.018a3.011 3.011 0 0 0 1.676-1.7v-.007a2.89 2.89 0 0 0 0-2.207 2.868 2.868 0 0 0-.27-.515c-.007-.012-.02-.025-.03-.039Zm6.137-3.373a2.53 2.53 0 0 1-.35.447L14.84 9.823c.112.428.166.869.16 1.311-.01.356-.06.709-.147 1.054l3.413 3.412c.132.134.249.283.347.444A9.88 9.88 0 0 0 20 11.269a9.912 9.912 0 0 0-1.386-5.319ZM14.6 19.264l-3.421-3.421c-.385.1-.781.152-1.18.157h-.134c-.356-.01-.71-.06-1.056-.147l-3.41 3.412a2.503 2.503 0 0 1-.443.347A9.884 9.884 0 0 0 9.732 21H10a9.9 9.9 0 0 0 5.044-1.388 2.519 2.519 0 0 1-.444-.348ZM1.735 15.6l3.426-3.426a4.608 4.608 0 0 1-.013-2.367L1.735 6.4a2.507 2.507 0 0 1-.35-.447 9.889 9.889 0 0 0 0 10.1c.1-.164.217-.316.35-.453Zm5.101-.758a4.957 4.957 0 0 1-.651-.645c-.033-.038-.077-.067-.11-.107L3.15 17.017a.5.5 0 0 0 0 .707l.127.127a.5.5 0 0 0 .706 0l2.932-2.933c-.03-.018-.05-.053-.078-.076ZM6.08 7.914c.03-.037.07-.063.1-.1.183-.22.384-.423.6-.609.047-.04.082-.092.129-.13L3.983 4.149a.5.5 0 0 0-.707 0l-.127.127a.5.5 0 0 0 0 .707L6.08 7.914Z" />
          </svg>
          <span class="ms-3">Help</span>
        </a>
      </li>
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