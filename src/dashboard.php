<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CrimeLess</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function toggleCollapse(id) {
            const element = document.getElementById(id);
            element.classList.toggle("hidden");
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- Include Sidebar from components folder -->
    <?php include '../components/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-4">Welcome to the Dashboard!</h1>

        <!-- Account Management Overview -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <h2 class="text-xl font-semibold mb-2">Account Management Overview</h2>
            <p class="text-gray-600">Manage your account settings, update your profile, or change your password.</p>
            <a href="#" class="text-blue-500 hover:underline mt-2 inline-block">Go to Account Settings</a>
        </div>

        <!-- Recent Alerts in ARC -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <h2 class="text-xl font-semibold mb-2">Recent Alerts in ARC</h2>
            <p class="text-gray-600">View recent alerts and collaborate with the community to ensure safety.</p>
            <ul class="list-disc pl-5 text-gray-600">
                <li>Alert: Suspicious activity reported near Park Street</li>
                <li>Alert: Traffic accident reported at Main Avenue</li>
            </ul>
            <a href="#" class="text-blue-500 hover:underline mt-2 inline-block">View All Alerts</a>
        </div>

        <!-- History & Reports Management -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <h2 class="text-xl font-semibold mb-2">History & Reports Management</h2>
            <p class="text-gray-600">Access your report history or generate new reports based on recent activities.</p>
            <a href="#" class="text-blue-500 hover:underline mt-2 inline-block">View History</a>
        </div>

        <!-- Latest News & Notifications -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <h2 class="text-xl font-semibold mb-2">Latest News & Notifications</h2>
            <p class="text-gray-600">Stay updated with the latest news and manage your notification settings.</p>
            <a href="#" class="text-blue-500 hover:underline mt-2 inline-block">Manage News & Notifications</a>
        </div>

        <!-- Gamification Challenges -->
        <div class="bg-white shadow-md rounded-lg p-4">
            <h2 class="text-xl font-semibold mb-2">Gamification Challenges</h2>
            <p class="text-gray-600">Complete challenges and climb the leaderboard to earn rewards.</p>
            <a href="#" class="text-blue-500 hover:underline mt-2 inline-block">View Challenges</a>
        </div>
    </div>

</body>
</html>
