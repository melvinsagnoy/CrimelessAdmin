<?php
session_start(); // Start the session for handling messages

// Database connection
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "crimeless_db"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search functionality
$searchQuery = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARC - Alert Report Collaboration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
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
        function openTab(tabName) {
            const tabs = document.querySelectorAll(".tab-content");
            tabs.forEach(tab => tab.classList.add("hidden"));
            document.getElementById(tabName).classList.remove("hidden");

            const tabButtons = document.querySelectorAll(".tab-button");
            tabButtons.forEach(button => button.classList.remove("active-tab"));
            document.getElementById(tabName + "-button").classList.add("active-tab");

            // Save the active tab in local storage
            localStorage.setItem("activeTab", tabName);
        }

        // Load the active tab from local storage when the page loads
        window.onload = function() {
            const activeTab = localStorage.getItem("activeTab") || "Urgent"; // Default to 'Urgent' tab
            openTab(activeTab);
        };

        function openUpdateModal(id, status) {
            document.getElementById('update-modal').style.display = 'flex';
            document.getElementById('update-id').value = id;
            document.getElementById('update-status').value = status;
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
        <h1 class="text-3xl font-bold mb-4">ARC - Alert Report Collaboration</h1>

        <!-- Folder-like Tab Buttons -->
        <div class="tab-container mb-6">
            <button id="Urgent-button" class="tab-button active-tab" onclick="openTab('Urgent')">Urgent</button>
            <button id="Violent-button" class="tab-button active-tab" onclick="openTab('Violent')">Violent</button>
            <button id="NonViolent-button" class="tab-button active-tab" onclick="openTab('NonViolent')">Non-Violent</button>
        </div>

        <!-- Search Form -->
        <form method="GET" class="mb-4">
            <input type="text" name="search" class="px-4 py-2 border rounded w-full" placeholder="Search by username or email" value="<?php echo $searchQuery; ?>">
            <button type="submit" class="px-4 py-2 bg-black text-white rounded mt-2">Search</button>
        </form>

        <!-- Tab Content for Urgent -->
        <div id="Urgent" class="tab-content">
            <h2 class="text-xl font-semibold mb-4">Urgent Alerts</h2>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">User Email</th>
                            <th class="py-3 px-6 text-left">Latitude</th>
                            <th class="py-3 px-6 text-left">Longitude</th>
                            <th class="py-3 px-6 text-left">Timestamp</th>
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <?php
                        $sql = "SELECT emergency.*, users.email FROM emergency 
                                JOIN users ON emergency.user_id = users.id 
                                WHERE emergency_type = 'urgent' 
                                AND (users.email LIKE '%$searchQuery%' OR users.username LIKE '%$searchQuery%')";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='border-b border-gray-200 hover:bg-gray-100'>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['latitude']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['longitude']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['timestamp']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td class='py-3 px-6 text-center'>";
                                echo "<button class='text-blue-500 hover:underline' onclick=\"openUpdateModal('". $row['id'] ."', '". htmlspecialchars($row['status']) ."')\">Update Status</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='py-3 px-6 text-center'>No urgent alerts found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Similar code for Violent and Non-Violent tabs with search feature -->
        <!-- Tab Content for Violent -->
        <div id="Violent" class="tab-content hidden">
            <h2 class="text-xl font-semibold mb-4">Violent Alerts</h2>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">User Email</th>
                            <th class="py-3 px-6 text-left">Latitude</th>
                            <th class="py-3 px-6 text-left">Longitude</th>
                            <th class="py-3 px-6 text-left">Timestamp</th>
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <?php
                        $sql = "SELECT emergency.*, users.email FROM emergency 
                                JOIN users ON emergency.user_id = users.id 
                                WHERE emergency_type = 'violent' 
                                AND (users.email LIKE '%$searchQuery%' OR users.username LIKE '%$searchQuery%')";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='border-b border-gray-200 hover:bg-gray-100'>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['latitude']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['longitude']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['timestamp']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td class='py-3 px-6 text-center'>";
                                echo "<button class='text-blue-500 hover:underline' onclick=\"openUpdateModal('". $row['id'] ."', '". htmlspecialchars($row['status']) ."')\">Update Status</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='py-3 px-6 text-center'>No violent alerts found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Content for Non-Violent -->
        <div id="NonViolent" class="tab-content hidden">
            <h2 class="text-xl font-semibold mb-4">Non-Violent Alerts</h2>
            <div class="bg-white shadow-md rounded-lg p-4">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">User Email</th>
                            <th class="py-3 px-6 text-left">Latitude</th>
                            <th class="py-3 px-6 text-left">Longitude</th>
                            <th class="py-3 px-6 text-left">Timestamp</th>
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <?php
                        $sql = "SELECT emergency.*, users.email FROM emergency 
                                JOIN users ON emergency.user_id = users.id 
                                WHERE emergency_type = 'non-violent' 
                                AND (users.email LIKE '%$searchQuery%' OR users.username LIKE '%$searchQuery%')";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='border-b border-gray-200 hover:bg-gray-100'>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['latitude']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['longitude']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['timestamp']) . "</td>";
                                echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td class='py-3 px-6 text-center'>";
                                echo "<button class='text-blue-500 hover:underline' onclick=\"openUpdateModal('". $row['id'] ."', '". htmlspecialchars($row['status']) ."')\">Update Status</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='py-3 px-6 text-center'>No non-violent alerts found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Update Modal for Editing Status -->
        <div id="update-modal" class="modal flex items-center justify-center">
            <div class="modal-content">
                <h2 class="text-xl font-semibold mb-4">Update Status</h2>
                <form action="../process/update_status.php" method="POST">
                    <input type="hidden" id="update-id" name="id">
                    <div class="mb-4">
                        <label for="update-status" class="block text-gray-700">Status:</label>
                        <select id="update-status" name="status" class="w-full px-3 py-2 border rounded">
                            <option value="pending">Pending</option>
                            <option value="responding">Responding</option>
                            <option value="responded">Responded</option>
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded mr-2" onclick="closeModal('update-modal')">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
