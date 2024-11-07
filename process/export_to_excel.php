<?php
session_start();

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

// Get the filtered data
$searchQuery = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

$params = [];
$types = '';

$sql = "SELECT emergency.id, users.email AS user_email, responders.email AS responder_email, 
        emergency.emergency_type, emergency.timestamp, emergency.status, emergency.responding_timestamp 
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

// Add date range filter if applicable
if (!empty($startDate) && !empty($endDate)) {
    $sql .= " AND emergency.responding_timestamp BETWEEN ? AND ?";
    $params[] = $startDate . " 00:00:00";
    $params[] = $endDate . " 23:59:59";
    $types .= 'ss';
}

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Set headers for Excel file
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=export_" . date('Y-m-d') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Print column names
echo "ID\tResponder Email\tEmergency Type\tTimestamp\tStatus\tUser Email\tResponded\n";

// Print rows
while ($row = $result->fetch_assoc()) {
    echo $row['id'] . "\t" . $row['responder_email'] . "\t" . $row['emergency_type'] . "\t" . $row['timestamp'] . "\t" .
        $row['status'] . "\t" . $row['user_email'] . "\t" . $row['responding_timestamp'] . "\n";
}

$stmt->close();
$conn->close();
?>
