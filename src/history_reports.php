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

$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

$analyticsStartDate = isset($_GET['analytics_start_date']) ? $_GET['analytics_start_date'] : '';
$analyticsEndDate = isset($_GET['analytics_end_date']) ? $_GET['analytics_end_date'] : '';

$params = [];
$types = '';

// Base SQL query for history and reports
$sql = "SELECT emergency.*, users.email AS user_email, responders.email AS responder_email 
        FROM emergency 
        JOIN users ON emergency.user_id = users.id 
        JOIN users AS responders ON emergency.responder_id = responders.id
        WHERE emergency.status = 'responded'";

// Add search filter if applicable
if (!empty($searchQuery)) {
    $sql .= " AND (users.email LIKE ? OR emergency.status LIKE ?)";
    $searchParam = '%' . $searchQuery . '%';
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= 'ss';
}

// Add date range filter for history and reports
if (!empty($startDate) && !empty($endDate)) {
    $sql .= " AND emergency.responding_timestamp BETWEEN ? AND ?";
    $params[] = $startDate . " 00:00:00"; // Start of the day
    $params[] = $endDate . " 23:59:59";   // End of the day
    $types .= 'ss';
}

// Prepare and execute the statement for history and reports
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch data for analytics
$analyticsSql = "SELECT emergency_type, COUNT(*) AS count 
                 FROM emergency 
                 WHERE status = 'responded'";

$analyticsParams = [];
$analyticsTypes = '';

// Add date range filter for analytics
if (!empty($analyticsStartDate) && !empty($analyticsEndDate)) {
    $analyticsSql .= " AND responding_timestamp BETWEEN ? AND ?";
    $analyticsParams[] = $analyticsStartDate . " 00:00:00"; // Start of the day
    $analyticsParams[] = $analyticsEndDate . " 23:59:59";   // End of the day
    $analyticsTypes .= 'ss';
}

$analyticsSql .= " GROUP BY emergency_type";

// Prepare and execute the statement for analytics
$analyticsStmt = $conn->prepare($analyticsSql);
if (!empty($analyticsParams)) {
    $analyticsStmt->bind_param($analyticsTypes, ...$analyticsParams);
}
$analyticsStmt->execute();
$analyticsResult = $analyticsStmt->get_result();

$chartData = [];
while ($row = $analyticsResult->fetch_assoc()) {
    $chartData[$row['emergency_type']] = (int) $row['count'];
}

// Prepare chart data for JavaScript
$chartLabels = json_encode(array_keys($chartData));
$chartCounts = json_encode(array_values($chartData));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History and Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
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
            max-width: 600px;
            width: 100%;
        }
        canvas {
            border-radius: 10px;
        }
    </style>
    <script>
        function openAnalyticsModal() {
            document.getElementById('analytics-modal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('analytics-modal').style.display = 'none';
        }

        function renderChart() {
        const chartType = document.getElementById('chartType').value;
        const ctx = document.getElementById('analyticsChart').getContext('2d');

        const chartData = {
            labels: <?php echo $chartLabels; ?>, // Use PHP-generated labels
            datasets: [{
                label: 'Responded Alerts',
                data: <?php echo $chartCounts; ?>, // Use PHP-generated counts
                backgroundColor: [
                    'rgba(255, 182, 193, 0.8)',
                    'rgba(135, 206, 250, 0.8)',
                    'rgba(144, 238, 144, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 105, 180, 1)',
                    'rgba(70, 130, 180, 1)',
                    'rgba(60, 179, 113, 1)'
                ],
                borderWidth: 2,
                hoverOffset: 5,
            }]
        };

        if (window.myChart) {
            window.myChart.destroy();
        }

        window.myChart = new Chart(ctx, {
            type: chartType,
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: { font: { size: 14 } }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        borderRadius: 5,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(200, 200, 200, 0.5)' },
                        ticks: { font: { size: 12 } }
                    },
                    x: {
                        ticks: { font: { size: 12 } }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutBounce'
                }
            }
        });
    }
    function exportChartToPDF() {
    // Get the chart type and the current date
    const chartType = document.getElementById('chartType').value;
    const currentDate = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD

    // Generate the filename
    const fileName = `${chartType}_chart_${currentDate}.pdf`;

    // Use html2canvas to capture the chart as an image
    html2canvas(document.getElementById('analyticsChart')).then(canvas => {
        // Convert the canvas to an image
        const imgData = canvas.toDataURL('image/png');

        // Create a new jsPDF instance
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();

        // Add the image to the PDF
        pdf.addImage(imgData, 'PNG', 10, 10, 180, 90); // Adjust positioning and size as needed

        // Save the PDF with the generated filename
        pdf.save(fileName);
    });
}
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">
    <?php include '../components/sidebar.php'; ?>

    <div class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-4">History and Reports</h1>

        <!-- Search Form -->
        <form method="GET" class="mb-4">
            <input type="text" name="search" class="px-4 py-2 border rounded w-full" placeholder="Search by user email or status" value="<?php echo $searchQuery; ?>">
            <button type="submit" class="px-4 py-2 bg-black text-white rounded mt-2">Search</button>
        </form>

        <!-- Date Range Filters -->
        <form method="GET" class="mb-4 flex space-x-4">
            <div>
                <label for="start_date" class="block mb-2">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="px-4 py-2 border rounded" value="<?php echo htmlspecialchars($startDate); ?>">
            </div>
            <div>
                <label for="end_date" class="block mb-2">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="px-4 py-2 border rounded" value="<?php echo htmlspecialchars($endDate); ?>">
            </div>
            <div class="self-end">
                <button type="submit" class="px-4 py-2 bg-black text-white rounded">Filter</button>
            </div>
        </form>

        <!-- Responded Alerts Table -->
        <h2 class="text-xl font-semibold mb-4">Responded Alerts</h2>
        <div class="bg-white shadow-md rounded-lg p-4">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Responder Email</th>
                        <th class="py-3 px-6 text-left">Emergency Type</th>
                        <th class="py-3 px-6 text-left">Timestamp</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-left">User Email</th>
                        <th class="py-3 px-6 text-left">Responded</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='border-b border-gray-200 hover:bg-gray-100'>";
                            echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['responder_email']) . "</td>";
                            echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['emergency_type']) . "</td>";
                            echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['timestamp']) . "</td>";
                            echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['user_email']) . "</td>";
                            echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row['responding_timestamp']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='py-3 px-6 text-center'>No responded alerts found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-4 space-x-4">
    <button onclick="openAnalyticsModal()" class="px-4 py-2 bg-blue-500 text-white rounded">Show Analytics</button>
    <a href="../process/export_to_excel.php?search=<?php echo urlencode($searchQuery); ?>&start_date=<?php echo $startDate; ?>&end_date=<?php echo $endDate; ?>" class="px-4 py-2 bg-green-500 text-white rounded">Export to Excel</a>
</div>

    </div>

   <!-- Analytics Modal -->
<div id="analytics-modal" class="modal flex">
    <div class="modal-content">
        <h2 class="text-xl font-semibold mb-4">Select Chart Type</h2>

        <div class="mb-4">
            <label for="chartType" class="block mb-2">Choose a chart type:</label>
            <select id="chartType" class="w-full px-3 py-2 border rounded">
                <option value="pie">Pie Chart</option>
                <option value="bar">Bar Graph</option>
                <option value="line">Area Chart</option>
            </select>
        </div>

        <canvas id="analyticsChart" width="400" height="200"></canvas>
        <button onclick="renderChart()" class="mt-4 px-4 py-2 bg-black text-white rounded">Render Chart</button>
        <button onclick="exportChartToPDF()" class="mt-4 px-4 py-2 bg-black text-white rounded">Export to PDF</button>
        <button onclick="closeModal()" class="mt-4 px-4 py-2 bg-red-500 text-white rounded">Close</button>
    </div>
</div>
</body>
</html>
