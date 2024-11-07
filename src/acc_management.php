<?php
session_start(); // Start the session to access session variables
include '../conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management - CrimeLess</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Styling for tab buttons and modal */
        .tab-button {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-color: #E5E7EB;
            color: #374151;
            padding: 0.5rem 1rem;
            font-weight: bold;
            transition: background-color 0.3s, color 0.3s;
        }
        .active-tab {
            background-color: black;
            color: white;
        }
        .inactive-tab {
            background-color: #E5E7EB;
            color: #374151;
        }
        .tab-container {
            display: flex;
            border-bottom: 2px solid #D1D5DB;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            width: 100%;
        }
    </style>
    <script>
        // Function to open a tab and save the active tab in local storage
        function openTab(tabName) {
            // Hide all tab content
            const tabs = document.querySelectorAll(".tab-content");
            tabs.forEach(tab => tab.classList.add("hidden"));

            // Show the selected tab content
            document.getElementById(tabName).classList.remove("hidden");

            // Update the active tab button
            const tabButtons = document.querySelectorAll(".tab-button");
            tabButtons.forEach(button => button.classList.remove("active-tab"));
            document.getElementById(tabName + "-button").classList.add("active-tab");

            // Save the active tab in local storage
            localStorage.setItem("activeTab", tabName);
        }

        // Load the active tab from local storage when the page loads
        window.onload = function() {
            const activeTab = localStorage.getItem("activeTab") || "Civilians"; // Default to 'Civilians' tab
            openTab(activeTab);
        };

        function openEditModal(id, username, email, phone, address) {
            document.getElementById('edit-modal').style.display = 'flex';
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-username').value = username;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-phone').value = phone;
            document.getElementById('edit-address').value = address;
        }

        function openAddResponderModal() {
            document.getElementById('add-responder-modal').style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- Include Sidebar from components folder -->
    <?php include '../components/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-4">Account Management</h1>

        <!-- Modern Alerts for Success and Error Messages -->
        <?php
        if (isset($_SESSION['success_message'])) {
            echo "
            <div class='flex items-center justify-between bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg shadow-sm mb-4' role='alert'>
                <div class='flex items-center'>
                    <svg class='w-6 h-6 mr-2 text-green-500' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path>
                    </svg>
                    <p class='font-semibold'>" . $_SESSION['success_message'] . "</p>
                </div>
                <button class='text-green-500 hover:text-green-700 focus:outline-none' onclick='this.parentElement.remove();'>
                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'></path>
                    </svg>
                </button>
            </div>";
            unset($_SESSION['success_message']);
        }

        if (isset($_SESSION['error_message'])) {
            echo "
            <div class='flex items-center justify-between bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg shadow-sm mb-4' role='alert'>
                <div class='flex items-center'>
                    <svg class='w-6 h-6 mr-2 text-red-500' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'></path>
                    </svg>
                    <p class='font-semibold'>" . $_SESSION['error_message'] . "</p>
                </div>
                <button class='text-red-500 hover:text-red-700 focus:outline-none' onclick='this.parentElement.remove();'>
                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'></path>
                    </svg>
                </button>
            </div>";
            unset($_SESSION['error_message']);
        }
        ?>

        <!-- Folder-like Tab Buttons -->
        <div class="tab-container mb-6">
            <button id="Civilians-button" class="tab-button active-tab" onclick="openTab('Civilians')">Civilians</button>
            <button id="Responders-button" class="tab-button active-tab" onclick="openTab('Responders')">Responders</button>
        </div>

        <!-- Search Form -->
        <form method="GET" class="mb-4">
            <input type="text" name="search" class="px-4 py-2 border rounded w-full" placeholder="Search by username or email">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded mt-2">Search</button>
        </form>

        <!-- Tab Content for Civilians -->
        <div id="Civilians" class="tab-content">
            <h2 class="text-xl font-semibold mb-4">Civilian Users</h2>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">Username</th>
                            <th class="py-3 px-6 text-left">Email</th>
                            <th class="py-3 px-6 text-left">Phone</th>
                            <th class="py-3 px-6 text-left">Address</th>
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <?php
                        $search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
                        $sql = "SELECT * FROM users WHERE role = 'Civilian' AND (username LIKE '%$search%' OR email LIKE '%$search%')";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr class='border-b border-gray-200 hover:bg-gray-100'>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['username']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['phone']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['address']) . "</td>";
                                echo "<td class='py-3 px-6 text-center'>";
                                echo "<button class='text-blue-500 hover:underline' onclick=\"openEditModal('". $row['id'] ."', '". htmlspecialchars($row['username']) ."', '". htmlspecialchars($row['email']) ."', '". htmlspecialchars($row['phone']) ."', '". htmlspecialchars($row['address']) ."')\">Edit</button> | ";
                                echo "<a href='../process/delete_user.php?id=" . $row['id'] . "' class='text-red-500 hover:underline' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='py-3 px-6 text-center'>No civilian users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Content for Responders -->
        <div id="Responders" class="tab-content hidden">
            <h2 class="text-xl font-semibold mb-4">Responder Users</h2>
            <button class="px-4 py-2 bg-black text-white rounded mb-4" onclick="openAddResponderModal()">Add Responder</button>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">Username</th>
                            <th class="py-3 px-6 text-left">Email</th>
                            <th class="py-3 px-6 text-left">Phone</th>
                            <th class="py-3 px-6 text-left">Address</th>
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <?php
                        $sql = "SELECT * FROM users WHERE role = 'Responder' AND (username LIKE '%$search%' OR email LIKE '%$search%')";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr class='border-b border-gray-200 hover:bg-gray-100'>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['username']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['phone']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['address']) . "</td>";
                                echo "<td class='py-3 px-6 text-center'>";
                                echo "<button class='text-blue-500 hover:underline' onclick=\"openEditModal('". $row['id'] ."', '". htmlspecialchars($row['username']) ."', '". htmlspecialchars($row['email']) ."', '". htmlspecialchars($row['phone']) ."', '". htmlspecialchars($row['address']) ."')\">Edit</button> | ";
                                echo "<a href='../process/delete_user.php?id=" . $row['id'] . "' class='text-red-500 hover:underline' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='py-3 px-6 text-center'>No responder users found</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Modal for Editing User -->
        <div id="edit-modal" class="modal flex items-center justify-center">
            <div class="modal-content">
                <h2 class="text-xl font-semibold mb-4">Edit User</h2>
                <form action="../process/update_user.php" method="POST">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="mb-4">
                        <label for="edit-username" class="block text-gray-700">Username:</label>
                        <input type="text" id="edit-username" name="username" class="w-full px-3 py-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label for="edit-email" class="block text-gray-700">Email:</label>
                        <input type="email" id="edit-email" name="email" class="w-full px-3 py-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label for="edit-phone" class="block text-gray-700">Phone:</label>
                        <input type="text" id="edit-phone" name="phone" class="w-full px-3 py-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label for="edit-address" class="block text-gray-700">Address:</label>
                        <input type="text" id="edit-address" name="address" class="w-full px-3 py-2 border rounded">
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded mr-2" onclick="closeModal('edit-modal')">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Responder Modal -->
        <div id="add-responder-modal" class="modal flex items-center justify-center">
            <div class="modal-content">
                <h2 class="text-xl font-semibold mb-4">Add Responder</h2>
                <form action="../process/add_responder.php" method="POST">
                    <div class="mb-4">
                        <label for="responder-username" class="block text-gray-700">Username:</label>
                        <input type="text" id="responder-username" name="username" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="responder-email" class="block text-gray-700">Email:</label>
                        <input type="email" id="responder-email" name="email" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="responder-phone" class="block text-gray-700">Phone:</label>
                        <input type="text" id="responder-phone" name="phone" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="responder-address" class="block text-gray-700">Address:</label>
                        <input type="text" id="responder-address" name="address" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="responder-role" class="block text-gray-700">Role:</label>
                        <input type="text" id="responder-role" name="role" class="w-full px-3 py-2 border rounded bg-gray-100" value="Responder" readonly>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="px-4 py-2 bg-red-500 text-white rounded mr-2" onclick="closeModal('add-responder-modal')">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-black text-white rounded">Add Responder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
