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
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    $role = "Responder"; // Set the role as 'Responder'

    // Generate a default password and hash it
    $defaultPassword = "password123"; // You can change this to a more secure default
    $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the new responder
    $sql = "INSERT INTO users (username, email, phone, address, role, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $username, $email, $phone, $address, $role, $hashedPassword);

    if ($stmt->execute()) {
        // If the insertion is successful, set a success message
        $_SESSION['success_message'] = "Responder added successfully with a default password (password123)!";
    } else {
        // If the insertion fails, set an error message
        $_SESSION['error_message'] = "Failed to add responder. Please try again.";
    }

    $stmt->close();
    // Redirect back to the account management page
    header("Location: ../src/acc_management.php");
    exit();
}

$conn->close();
?>
