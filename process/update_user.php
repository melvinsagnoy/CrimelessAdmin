<?php
// Start a session to handle feedback messages
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $id = htmlspecialchars($_POST['id']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);

    // Update the user information in the database
    $sql = "UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $email, $phone, $address, $id);

    if ($stmt->execute()) {
        // If the update is successful, set a success message
        $_SESSION['success_message'] = "User updated successfully!";
    } else {
        // If the update fails, set an error message
        $_SESSION['error_message'] = "Failed to update user. Please try again.";
    }

    $stmt->close();
    // Redirect back to the account management page
    header("Location: ../src/acc_management.php");
    exit();
}

$conn->close();
?>
