<?php
session_start(); // Start session to use session variables

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

// Check if the ID is set and not empty
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the input

    // Prepare and execute the delete query
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // If deletion is successful, set a success message
        $_SESSION['success_message'] = "User deleted successfully!";
    } else {
        // If deletion fails, set an error message
        $_SESSION['error_message'] = "Failed to delete user. Please try again.";
    }

    $stmt->close();
} else {
    // If ID is not set, set an error message
    $_SESSION['error_message'] = "Invalid user ID.";
}

// Redirect back to the account management page
header("Location: ../src/acc_management.php");
exit();

$conn->close();
?>
