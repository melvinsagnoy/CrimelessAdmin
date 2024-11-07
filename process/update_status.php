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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $id = htmlspecialchars($_POST['id']);
    $status = htmlspecialchars($_POST['status']);

    // Update the status in the emergency table
    $sql = "UPDATE emergency SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        // If the update is successful, set a success message
        $_SESSION['success_message'] = "Status updated successfully!";
    } else {
        // If the update fails, set an error message
        $_SESSION['error_message'] = "Failed to update status. Please try again.";
    }

    $stmt->close();
    // Redirect back to the ARC management page
    header("Location: ../src/arc_management.php");
    exit();
}

$conn->close();
?>
