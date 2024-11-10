<?php
session_start();
include '../conn.php'; // Database connection

// Fetch the count of civilians from the database
$civilianQuery = "SELECT COUNT(*) AS count FROM users WHERE role = 'civilian'";
$civilianResult = $conn->query($civilianQuery);
$civilianCount = $civilianResult->fetch_assoc()['count'];

// Fetch the count of responders from the database
$responderQuery = "SELECT COUNT(*) AS count FROM users WHERE role = 'responder'";
$responderResult = $conn->query($responderQuery);
$responderCount = $responderResult->fetch_assoc()['count'];

// Fetch the count of admins from the admin table
$adminQuery = "SELECT COUNT(*) AS count FROM admin";
$adminResult = $conn->query($adminQuery);
$adminCount = $adminResult->fetch_assoc()['count'];


// Fetch recent emergencies with usernames
$emergencyQuery = "
    SELECT e.id, u.username, e.emergency_type, e.timestamp, e.status
    FROM emergency e
    JOIN users u ON e.user_id = u.id
    ORDER BY e.timestamp DESC
    LIMIT 5";
$emergencyResult = $conn->query($emergencyQuery);

// Fetch the leaderboard data
$leaderboardQuery = "
    SELECT u.username, SUM(g.score) AS total_score
    FROM game_data g
    JOIN users u ON g.user_id = u.id
    GROUP BY g.user_id
    ORDER BY total_score DESC
    LIMIT 10";
$leaderboardResult = $conn->query($leaderboardQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CrimeLess</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.6/lottie.min.js"></script> <!-- Lottie Library -->
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
        .content-scrollable {
            overflow-y: auto;
            height: 100vh;
        }
        .card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        .lottie-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 0.2; /* Adjust opacity to make the animation subtle */
        }
        .card-content {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body class="min-h-screen flex">

    <!-- Include Sidebar -->
    <?php include '../components/sidebar.php'; ?>
    <!-- Main Content Area -->
    <div class="flex-1 p-6 content-scrollable flex">
        
        <!-- Left Content Area -->
        <div class="flex-1 pr-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Civilians Card with Gradient Background -->
                <div class="card text-center bg-gradient-to-r from-blue-400 to-blue-600 text-white">
                <h3 class="text-sm font-medium">Civilians</h3>
                <p class="text-2xl font-semibold"><?php echo $civilianCount; ?></p>
                <img src="../assets/civilian.png" alt="Civilians" class="absolute top-2 right-2 w-12 h-12 opacity-40">
                </div>
                <!-- Responders Card with Gradient Background -->
                <div class="card text-center bg-gradient-to-r from-green-400 to-green-600 text-white">
                <h3 class="text-sm font-medium">Responders</h3>
                <p class="text-2xl font-semibold"><?php echo $responderCount; ?></p>
                <img src="../assets/responder.png" alt="Responders" class="absolute top-2 right-2 w-12 h-12 opacity-40">
                </div>
                <!-- Admins Card with Gradient Background -->
                <div class="card text-center bg-gradient-to-r from-purple-400 to-purple-600 text-white">
                <h3 class="text-sm font-medium">Admins</h3>
                <p class="text-2xl font-semibold"><?php echo $adminCount; ?></p>
                <img src="../assets/admin.png" alt="Admins" class="absolute top-2 right-2 w-12 h-12 opacity-40">
                </div>
            </div>

            <!-- Main Cards Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Account Management Card with Lottie Animation -->
                <div class="card bg-gradient-to-r from-indigo-400 to-indigo-600 text-white">
                    <div class="lottie-container" id="lottie-animation"></div>
                    <div class="card-content">
                        <h2 class="text-lg font-semibold mb-2">Account Management</h2>
                        <p class="mb-4">Manage and oversee user accounts, including creating, updating, or removing users.</p>
                        <a href="./acc_management.php"><button class="bg-white text-indigo-600 rounded-lg py-2 px-4">Manage Users</button></a>
                    </div>
                </div>

                <!-- ARC Management Card with Lottie Animation -->
                <div class="card bg-gradient-to-r from-red-400 to-red-600 text-white">
                    <div class="lottie-container" id="arc-lottie-animation"></div>
                    <div class="card-content">
                        <h2 class="text-lg font-semibold mb-2">ARC Management</h2>
                        <p class="mb-4">Manage emergency alert data categorized into non-violent, violent, and urgent alerts.</p>
                        <a href="./arc_management.php"><button class="bg-white text-red-600 rounded-lg py-2 px-4">View Alerts</button></a>
                    </div>
                </div>

                <!-- History and Reports Card with Lottie Animation -->
                <div class="card bg-gradient-to-r from-yellow-400 to-yellow-600 text-white">
                    <div class="lottie-container" id="history-lottie-animation"></div>
                    <div class="card-content">
                        <h2 class="text-lg font-semibold mb-2">History and Reports</h2>
                        <p class="mb-4">View the history of alerts and generate reports for analysis.</p>
                        <a href="./history_reports.php"><button class="bg-white text-yellow-600 rounded-lg py-2 px-4">View History</button></a>
                    </div>
                </div>
            </div>

            <!-- New Row with Game Management and News and Notifications Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <!-- News and Notifications Card with Gradient Background -->
                <div class="card bg-gradient-to-r from-purple-300 to-purple-500 text-white">
                    <div class="lottie-container" id="news-lottie-animation"></div>
                    <div class="card-content">
                        <h2 class="text-lg font-semibold mb-2">News and Notifications</h2>
                        <p class="mb-4">Stay updated with the latest news and manage notifications for the community.</p>
                        <a href="./newsfeed.php"><button class="bg-white text-purple-600 rounded-lg py-2 px-4">View News</button></a>
                    </div>
                </div>
               <!-- Game Management Card with Gray Gradient Background -->
            <div class="card bg-gradient-to-r from-gray-300 to-gray-500 text-white">
                <div class="lottie-container" id="game-lottie-animation"></div>
                <div class="card-content">
                    <h2 class="text-lg font-semibold mb-2">Game Management</h2>
                    <p class="mb-4">Manage game features, including adding and updating questions for user challenges.</p>
                    <a href="./game_management.php"><button class="bg-white text-gray-600 rounded-lg py-2 px-4">Add Questions</button></a>
                </div>
            </div>
            </div>

           <!-- Recent Emergencies Card -->
           <div class="card bg-gradient-to-r from-gray-400 to-gray-600 text-white mt-6">
                <h2 class="text-lg font-semibold mb-2">Recent Emergencies</h2>
                <table class="w-full text-left text-white">
                    <thead>
                        <tr class="text-gray-300">
                            <th class="py-2">Username</th>
                            <th class="py-2">Type</th>
                            <th class="py-2">Timestamp</th>
                            <th class="py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $emergencyResult->fetch_assoc()): ?>
                            <tr class="text-white">
                                <td class="py-2"><?php echo htmlspecialchars($row['username']); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($row['emergency_type']); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($row['timestamp']); ?></td>
                                <td class="py-2">
                                <?php
                                    $status = htmlspecialchars($row['status']);
                                    $statusColor = '';
                                    if ($status === 'responded') {
                                        $statusColor = 'text-green-400';
                                    } elseif ($status === 'responding') {
                                        $statusColor = 'text-blue-400';
                                    } elseif ($status === 'pending') {
                                        $statusColor = 'text-red-400';
                                    }
                                        ?>
                                        <span class="<?php echo $statusColor; ?>"><?php echo $status; ?></span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="w-80 bg-gradient-to-r from-yellow-700 to-yellow-500 text-white shadow-lg rounded-xl p-6">
            <h2 class="text-xl font-bold mb-4">Leaderboards</h2>
            <div class="space-y-2">
                <?php $rank = 1; ?>
                <?php while ($row = $leaderboardResult->fetch_assoc()): ?>
                    <div class="flex items-center justify-between bg-white bg-opacity-20 rounded-lg p-2 hover:bg-opacity-30 transition">
                        <div class="flex items-center space-x-3">
                            <span class="text-lg font-semibold"><?php echo $rank; ?>.</span>
                            <span class="font-medium"><?php echo htmlspecialchars($row['username']); ?></span>
                        </div>
                        <span class="font-bold text-lg"><?php echo htmlspecialchars($row['total_score']); ?> pts</span>
                    </div>
                    <?php $rank++; ?>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <script>
        // Load Lottie Animation for Account Management
        lottie.loadAnimation({
            container: document.getElementById('lottie-animation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '../assets/lottie/user.json'
        });

        // Load Lottie Animation for ARC Management
        lottie.loadAnimation({
            container: document.getElementById('arc-lottie-animation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '../assets/lottie/alert.json'
        });

        // Load Lottie Animation for History and Reports
        lottie.loadAnimation({
            container: document.getElementById('history-lottie-animation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '../assets/lottie/history.json'
        });

        // Load Lottie Animation for News and Notifications
        lottie.loadAnimation({
            container: document.getElementById('news-lottie-animation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '../assets/lottie/news.json'
        });
        
        // Load Lottie Animation for Game Management
        lottie.loadAnimation({
            container: document.getElementById('game-lottie-animation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '../assets/lottie/game.json' // Path to your Lottie JSON file for Game Management
        });
    </script>
</body>
</html>
