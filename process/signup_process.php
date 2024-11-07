<?php
// Database connection
$servername = "localhost"; // Adjust as needed
$username = "root";        // Database username
$password = "";            // Database password
$dbname = "crimeless_db";  // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve user inputs
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $address = htmlspecialchars($_POST['address']);
    $photo = $_FILES['photo']['name'];

    // Move uploaded photo to the desired directory
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($photo);
    
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("INSERT INTO admin (name, email, phone, username, password, address, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");

        // Check if prepare() failed
        if (!$stmt) {
            die("Prepare failed: " . $conn->error); // Output error message
        }

        $stmt->bind_param("sssssss", $name, $email, $phone, $username, $password, $address, $photo);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to a thank you page after successful registration
            header("Location: ../messages/thank_you.php");
            exit();
        } else {
            // Redirect to an error page if insertion fails
            header("Location: ../messages/error.php");
            exit();
        }

        // Close the statement
        $stmt->close();
    } else {
        // Redirect to an error page if file upload fails
        header("Location: ../messages/error.php");
        exit();
    }
}

// Close the connection
$conn->close();
?>
