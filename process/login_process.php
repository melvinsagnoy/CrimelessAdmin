<?php
session_start(); // Start the session

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
    // Google reCAPTCHA verification
    $secretKey = "6LdomncqAAAAAPvQQ8D-9zMoQmsd4KphscEiDb16"; // Replace with your actual Secret Key from Google
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Make a request to the Google reCAPTCHA API
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
        $_SESSION['error_message'] = "Please complete the reCAPTCHA verification.";
        header("Location: ../login.php");
        exit();
    }

    // Sanitize and retrieve user inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Check if inputs are empty
    if (empty($username) || empty($password)) {
        $_SESSION['error_message'] = "Please fill in both username and password.";
        header("Location: ../login.php");
        exit();
    }

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user is found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start the session and store user info
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['success_message'] = "Successfully logged in!";
            header("Location: ../src/dashboard.php"); // Redirect to the dashboard in src folder
            exit();
        } else {
            // Invalid password
            $_SESSION['error_message'] = "Incorrect username or password.";
            header("Location: ../login.php");
            exit();
        }
    } else {
        // User not found
        $_SESSION['error_message'] = "Incorrect username or password.";
        header("Location: ../login.php");
        exit();
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
