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
                                        <td class='px-6 py-4'>
                                            <!-- View Button -->
                                            <button data-modal-target="viewDocumentModal<?php echo $row['id']; ?>" data-modal-toggle="viewDocumentModal<?php echo $row['id']; ?>" class='text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700'>
                                                <i class="fi fi-rr-document me-2"></i>
                                                View
                                            </button>
                                            <!-- Edit Button -->
                                            <button data-modal-target="editDocumentModal<?php echo $row['id']; ?>" data-modal-toggle="editDocumentModal<?php echo $row['id']; ?>" class='text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700'>
                                                <i class="fi fi-rr-edit me-2"></i>
                                                Edit
                                            </button>
                                            <!-- Delete Button -->
                                            <button data-modal-target="deleteDocumentModal<?php echo $row['id']; ?>" data-modal-toggle="deleteDocumentModal<?php echo $row['id']; ?>" class='text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700'>
                                                <i class="fi fi-rr-trash me-2"></i>
                                                Delete
                                            </button>
                                            <!-- Download Button -->
                                            <a href="download_document.php?id=<?php echo $row['id']; ?>" class='text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700'>
                                                <i class="fi fi-rr-download me-2"></i>
                                                Download
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- View Document Modal -->
                                    <div id="viewDocumentModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">View Document</h3>
                                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="viewDocumentModal<?php echo $row['id']; ?>">
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                </div>
                                                <div class="p-4 md:p-5 space-y-4">
                                                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                                        Document Name: <?php echo $row['document_name']; ?>
                                                    </p>
                                                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                                        Uploaded Date: <?php echo $row['uploaded_date']; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Edit Document Modal -->
                                    <div id="editDocumentModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Document</h3>
                                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="editDocumentModal<?php echo $row['id']; ?>">
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                </div>
                                                <div class="p-4 md:p-5 space-y-4">
                                                    <form id="editForm<?php echo $row['id']; ?>" action="edit_document.php" method="POST">
                                                        <input type="hidden" name="document_id" value="<?php echo $row['id']; ?>">
                                                        <label for="editDocumentName<?php echo $row['id']; ?>" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Document Name</label>
                                                        <input type="text" id="editDocumentName<?php echo $row['id']; ?>" name="document_name" value="<?php echo $row['document_name']; ?>" class="w-full p-2 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                                        <button type="submit" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 mt-4 mb-2 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Save Changes</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Delete Document Modal -->
                                    <div id="deleteDocumentModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Delete Document</h3>
                                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteDocumentModal<?php echo $row['id']; ?>">
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                </div>
                                                <div class="p-4 md:p-5 space-y-4">
                                                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                                        Are you sure you want to delete the document "<?php echo $row['document_name']; ?>"?
                                                    </p>
                                                    <div class="flex justify-end space-x-4">
                                                        <form id="deleteForm<?php echo $row['id']; ?>" action="delete_document.php" method="POST">
                                                            <input type="hidden" name="document_id" value="<?php echo $row['id']; ?>">
                                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">Yes, delete</button>
                                                        </form>
                                                        <button type="button" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" data-modal-hide="deleteDocumentModal<?php echo $row['id']; ?>">Cancel</button>
                                                    </div>
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