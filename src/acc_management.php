<?php
include '../conn.php';
session_start();

// Fetch users with verification details, excluding users without a verify_id
$sql = "SELECT id, username, verify_status, verify_id FROM users WHERE verify_id IS NOT NULL AND verify_id != '';";
$result = $conn->query($sql);

// Fetch all rows into an array
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management - CrimeLess</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
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
    z-index: 9999;
}

.modal-content {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-height: 80%;
    overflow: hidden;
}

#photo-preview {
    margin: 0 auto;
    display: block;
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
    object-fit: contain;
}
    </style>
    <script>
          function openVerificationModal() {
            document.getElementById('verification-modal').style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function updateVerificationStatus(userId, status) {
            if (confirm(`Are you sure you want to ${status.toLowerCase()} this verification?`)) {
                fetch('../process/update_verification.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: userId,
                        status: status,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the verification status.');
                });
            }
        }

        function openPhotoModal(imageSrc) {
    const modal = document.getElementById('photo-modal');
    const img = document.getElementById('photo-preview');
    img.src = imageSrc; // Dynamically set the image source
    modal.style.display = 'flex'; // Display the modal
}
        // Tabs navigation
        function openTab(tabName) {
            const tabs = document.querySelectorAll(".tab-content");
            tabs.forEach(tab => tab.classList.add("hidden"));
            document.getElementById(tabName).classList.remove("hidden");
            const tabButtons = document.querySelectorAll(".tab-button");
            tabButtons.forEach(button => button.classList.remove("active-tab"));
            document.getElementById(tabName + "-button").classList.add("active-tab");
            localStorage.setItem("activeTab", tabName);
        }

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
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- Include Sidebar -->
    <?php include '../components/sidebar.php'; ?>

    <div class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-4">Account Management</h1>

        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<div class='bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-4'>" . $_SESSION['success_message'] . "</div>";
            unset($_SESSION['success_message']);
        }

        if (isset($_SESSION['error_message'])) {
            echo "<div class='bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-4'>" . $_SESSION['error_message'] . "</div>";
            unset($_SESSION['error_message']);
        }
        ?>

        <div class="flex justify-end mb-4">
            <button onclick="openVerificationModal()" class="px-4 py-2 bg-blue-500 text-white rounded">User Verification Management</button>
        </div>

        <!-- Tab Navigation -->
        <div class="tab-container mb-6">
            <button id="Civilians-button" class="tab-button active-tab" onclick="openTab('Civilians')">Civilians</button>
            <button id="Responders-button" class="tab-button inactive-tab" onclick="openTab('Responders')">Responders</button>
        </div>

        <!-- Civilians Tab -->
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
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <?php
                        $sql = "SELECT * FROM users WHERE role = 'Civilian'";
                        $result = $conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='border-b border-gray-200 hover:bg-gray-100'>";
                            echo "<td class='py-3 px-6'>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td class='py-3 px-6'>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td class='py-3 px-6'>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td class='py-3 px-6'>" . htmlspecialchars($row['phone']) . "</td>";
                            echo "<td class='py-3 px-6'>" . htmlspecialchars($row['address']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
<!-- Verification Management Modal -->
<div id="verification-modal" class="modal">
  <div class="modal-content p-6 w-full max-w-4xl mx-auto bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-4 text-gray-700">User Verification Management</h2>
    <div class="overflow-y-auto max-h-96">
      <table class="min-w-full bg-white border border-gray-200">
        <thead>
          <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
            <th class="py-3 px-6 text-left">ID</th>
            <th class="py-3 px-6 text-left">Username</th>
            <th class="py-3 px-6 text-left">Verification Status</th>
            <th class="py-3 px-6 text-left">View Photo</th>
            <th class="py-3 px-6 text-center">Actions</th>
          </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
          <?php foreach ($users as $user): ?>
            <?php
              $verifyId = htmlspecialchars($user['verify_id']);
              $imagePath = "./uploads/verification/" . $verifyId;
              $fileExists = !empty($verifyId) && file_exists($imagePath);
              if (!$fileExists) continue;
            ?>
            <tr class="border-b border-gray-200 hover:bg-gray-50">
              <td class="py-3 px-6"><?= htmlspecialchars($user['id']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($user['username']) ?></td>
              <td class="py-3 px-6">
                <span class="px-2 py-1 rounded text-white 
                  <?= $user['verify_status'] === 'Verified' ? 'bg-green-500' : ($user['verify_status'] === 'Rejected' ? 'bg-red-500' : 'bg-yellow-500') ?>">
                  <?= htmlspecialchars($user['verify_status']) ?>
                </span>
              </td>
              <td class="py-3 px-6">
                <button onclick="openPhotoModal('<?= $imagePath ?>')" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 focus:ring-2 focus:ring-blue-300">
                  View Photo
                </button>
              </td>
              <td class="py-3 px-6 text-center">
                <?php if ($user['verify_status'] !== 'Verified'): ?>
                  <button onclick="updateVerificationStatus(<?= $user['id'] ?>, 'Verified')" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 focus:ring-2 focus:ring-green-300">
                    Accept
                  </button>
                  <button onclick="updateVerificationStatus(<?= $user['id'] ?>, 'Rejected')" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 focus:ring-2 focus:ring-red-300">
                    Reject
                  </button>
                <?php else: ?>
                  <span class="text-green-500 font-bold">Verified</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <button onclick="closeModal('verification-modal')" class="mt-4 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 focus:ring-2 focus:ring-gray-300">
      Close
    </button>
  </div>
</div>

<!-- Photo Modal -->
<div id="photo-modal" class="modal">
  <div class="modal-content p-6 w-full max-w-lg mx-auto bg-white rounded-lg shadow-lg">
    <img id="photo-preview" src="" alt="Verification ID" class="w-full rounded-lg object-contain">
    <button onclick="closeModal('photo-modal')" class="mt-4 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 focus:ring-2 focus:ring-gray-300">
      Close
    </button>
  </div>
</div>


    <?php $conn->close(); ?>
</body>
</html>
