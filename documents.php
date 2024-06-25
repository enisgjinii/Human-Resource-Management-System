<?php include('check_auth.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentFlow - Documents</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.4.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-200">
    <?php include('layouts/sidebar.php'); ?>
    <div class="p-4 sm:ml-64 mt-14 text-white dark:bg-gray-800 p-4 rounded-lg">
        <div class="my-4">
            <!-- Document Upload Form -->
            <div class="mb-5 border border-black dark:border-gray-600 px-8 pt-5 rounded-xl bg-white dark:bg-gray-800 dark:text-white">
                <h2 class="text-xl mb-4 text-gray-900 dark:text-gray-200">Upload New Document</h2>
                <form id="uploadForm" action="upload_document.php" method="POST" enctype="multipart/form-data">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="documentName" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Document Name</label>
                            <input type="text" id="documentName" name="document_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 text-sm text-gray-900 dark:text-white" for="file_input">Upload file</label>
                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="documentFile" name="document_file" type="file" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="documentPassword" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Document Password (optional)</label>
                        <input type="password" id="documentPassword" name="document_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <button type="submit" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-5 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Upload</button>
                </form>
            </div>
            <!-- Document List -->
            <div class="mb-5 border border-black dark:border-gray-600 px-8 pt-5 rounded-xl bg-white dark:bg-gray-800 dark:text-white">
                <div class="mb-4">
                    <h2 class="text-xl mb-4 text-gray-900 dark:text-gray-200">Document List</h2>
                    <div class="mb-4">
                        <label for="searchDocument" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Search Documents</label>
                        <div class="flex items-center">
                            <input type="text" id="searchDocument" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter document name...">
                            <button id="voiceSearchBtn" class="ml-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <i class="fi fi-rr-microphone"></i>
                            </button>
                        </div>
                        <p id="voiceSearchStatus" class="mt-2 text-sm text-gray-500 dark:text-gray-400"></p>
                    </div>
                    <div class="relative overflow-x-auto border dark:border-gray-700 sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-4">#</th>
                                    <th class="px-6 py-4">Document Name</th>
                                    <th class="px-6 py-4">File Size</th>
                                    <th class="px-6 py-4">File Type</th>
                                    <th class="px-6 py-4">Uploaded Date</th>
                                    <th class="px-6 py-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="documentList">
                                <?php
                                include('connection.php');
                                $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
                                $query = "SELECT * FROM documents WHERE document_name LIKE '%$search%'";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class='px-6 py-4'><?php echo $row['id']; ?></td>
                                        <td class='px-6 py-4'><?php echo $row['document_name']; ?></td>
                                        <td class='px-6 py-4'><?php echo strtoupper($row['file_type']); ?></td>
                                        <td class='px-6 py-4'><?php echo round($row['file_size'] / 1024, 2); ?> KB</td>
                                        <td class='px-6 py-4'><?php echo $row['uploaded_date']; ?></td>
                                        <td class='px-6 py-4 flex space-x-2'>
                                            <!-- View Button -->
                                            <button data-modal-target="viewDocumentModal<?php echo $row['id']; ?>" data-modal-toggle="viewDocumentModal<?php echo $row['id']; ?>" class='flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50'>
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                View
                                            </button>
                                            <!-- Edit Button -->
                                            <button data-modal-target="editDocumentModal<?php echo $row['id']; ?>" data-modal-toggle="editDocumentModal<?php echo $row['id']; ?>" class='flex items-center justify-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50'>
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </button>
                                            <!-- Delete Button -->
                                            <button data-modal-target="deleteDocumentModal<?php echo $row['id']; ?>" data-modal-toggle="deleteDocumentModal<?php echo $row['id']; ?>" class='flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50'>
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                            <!-- Download Button -->
                                            <a href="download_document.php?id=<?php echo $row['id']; ?>" class='flex items-center justify-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50'>
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                Download
                                                </button>
                                        </td>
                                    </tr>
                                    <!-- View Document Modal -->
                                    <!-- View Document Modal -->
                                    <div id="viewDocumentModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                                            <div class="relative bg-white rounded-lg shadow-lg dark:bg-gray-700 transform transition-all duration-300 ease-in-out">
                                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-gray-50 dark:bg-gray-800">
                                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                        View Document
                                                    </h3>
                                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white transition-colors duration-200" data-modal-hide="viewDocumentModal<?php echo $row['id']; ?>">
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                </div>
                                                <div class="p-6 space-y-6">
                                                    <div class="bg-white dark:bg-gray-600 rounded-lg p-4 shadow-inner">
                                                        <p class="text-base leading-relaxed text-gray-700 dark:text-gray-300">
                                                            <span class="font-semibold">Document Name:</span> <?php echo $row['document_name']; ?>
                                                        </p>
                                                        <p class="text-base leading-relaxed text-gray-700 dark:text-gray-300 mt-4">
                                                            <span class="font-semibold">Uploaded Date:</span> <?php echo $row['uploaded_date']; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                    <button data-modal-hide="viewDocumentModal<?php echo $row['id']; ?>" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors duration-200">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Edit Document Modal -->
                                    <div id="editDocumentModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                                            <div class="relative bg-white rounded-lg shadow-lg dark:bg-gray-700 transform transition-all duration-300 ease-in-out">
                                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-gray-50 dark:bg-gray-800">
                                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                        Edit Document
                                                    </h3>
                                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white transition-colors duration-200" data-modal-hide="editDocumentModal<?php echo $row['id']; ?>">
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                </div>
                                                <form id="editForm<?php echo $row['id']; ?>" action="edit_document.php" method="POST">
                                                    <div class="p-6 space-y-6">
                                                        <input type="hidden" name="document_id" value="<?php echo $row['id']; ?>">
                                                        <div>
                                                            <label for="editDocumentName<?php echo $row['id']; ?>" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Document Name</label>
                                                            <input type="text" id="editDocumentName<?php echo $row['id']; ?>" name="document_name" value="<?php echo $row['document_name']; ?>" class="w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors duration-200 mr-3">Save Changes</button>
                                                        <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 transition-colors duration-200" data-modal-hide="editDocumentModal<?php echo $row['id']; ?>">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Delete Document Modal -->
                                    <div id="deleteDocumentModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-md max-h-full">
                                            <div class="relative bg-white rounded-lg shadow-lg dark:bg-gray-700 transform transition-all duration-300 ease-in-out">
                                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-red-50 dark:bg-red-800">
                                                    <h3 class="text-xl font-semibold text-red-700 dark:text-red-200">
                                                        Delete Document
                                                    </h3>
                                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white transition-colors duration-200" data-modal-hide="deleteDocumentModal<?php echo $row['id']; ?>">
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                </div>
                                                <div class="p-6 text-center">
                                                    <svg class="mx-auto mb-4 text-red-500 w-12 h-12 dark:text-red-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete the document "<?php echo $row['document_name']; ?>"?</h3>
                                                    <form id="deleteForm<?php echo $row['id']; ?>" action="delete_document.php" method="POST" class="inline-block">
                                                        <input type="hidden" name="document_id" value="<?php echo $row['id']; ?>">
                                                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2 transition-colors duration-200">
                                                            Yes, I'm sure
                                                        </button>
                                                    </form>
                                                    <button data-modal-hide="deleteDocumentModal<?php echo $row['id']; ?>" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 transition-colors duration-200">No, cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main modal script -->
    <script>
        // Function to show modal by ID
        function showModal(modalId) {
            $('#' + modalId).removeClass('hidden');
            // Get navbar and send back opacity
            var navbar = document.getElementById('navbar');
            navbar.style.opacity = '0.5';
            navbar.style.pointerEvents = 'none';
            // Get navbar and give z-index
            var navbar = document.getElementById('navbar');
        }
        // Function to hide modal by ID
        function hideModal(modalId) {
            $('#' + modalId).addClass('hidden');
        }
        // AJAX for getting document statistics
        // $.ajax({
        //     url: 'get_document_stats.php',
        //     type: 'GET',
        //     success: function(response) {
        //         const data = JSON.parse(response);
        //         const options = {
        //             chart: {
        //                 type: 'bar',
        //                 height: 350
        //             },
        //             series: [{
        //                 name: 'Documents',
        //                 data: data.counts
        //             }],
        //             xaxis: {
        //                 categories: data.months
        //             }
        //         };
        //         const chart = new ApexCharts(document.querySelector("#chart"), options);
        //         chart.render();
        //     }
        // });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchDocument');
            const documentList = document.getElementById('documentList');
            const voiceSearchBtn = document.getElementById('voiceSearchBtn');
            const voiceSearchStatus = document.getElementById('voiceSearchStatus');
            // Text search functionality
            searchInput.addEventListener('input', function() {
                performSearch(this.value);
            });
            // Voice search functionality
            voiceSearchBtn.addEventListener('click', startVoiceRecognition);
            function startVoiceRecognition() {
                if ('webkitSpeechRecognition' in window) {
                    const recognition = new webkitSpeechRecognition();
                    recognition.lang = 'sq-AL'; // Set language to Albanian
                    recognition.continuous = false;
                    recognition.interimResults = false;
                    recognition.onstart = function() {
                        voiceSearchStatus.textContent = 'Ju lutem flisni...';
                        voiceSearchBtn.classList.add('bg-red-500');
                    };
                    recognition.onerror = function(event) {
                        voiceSearchStatus.textContent = 'Gabim në njohjen e zërit. Ju lutem provoni përsëri.';
                        voiceSearchBtn.classList.remove('bg-red-500');
                    };
                    recognition.onend = function() {
                        voiceSearchStatus.textContent = 'Njohja e zërit përfundoi.';
                        voiceSearchBtn.classList.remove('bg-red-500');
                    };
                    recognition.onresult = function(event) {
                        const transcript = event.results[0][0].transcript;
                        searchInput.value = transcript;
                        voiceSearchStatus.textContent = 'Kërkimi për: ' + transcript;
                        performSearch(transcript);
                    };
                    recognition.start();
                } else {
                    voiceSearchStatus.textContent = 'Njohja e zërit nuk mbështetet në këtë shfletues.';
                }
            }
            function performSearch(searchTerm) {
                fetch(`get_documents.php?search=${encodeURIComponent(searchTerm)}`)
                    .then(response => response.text())
                    .then(html => {
                        documentList.innerHTML = html;
                        if (typeof initFlowbite === 'function') {
                            initFlowbite();
                        }
                    });
            }
        });
    </script>
    <!-- Include Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>