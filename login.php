<?php
session_start(); // Start the session

// Check if there's an error message in the session
$error_message = "";
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Clear the message after displaying
}

// Your Google reCAPTCHA Site Key
$siteKey = "6LdomncqAAAAACDiHJ8tF3m1pWT-xPu5AL-XSS_q"; // Replace with your actual Site Key from Google
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CrimeLess</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-gradient-to-r from-red-500 to-blue-500 text-white min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 rounded-lg shadow-lg p-8 max-w-md w-full">
        <h2 class="text-3xl font-bold text-center mb-6">Login to CrimeLess</h2>
        
        <?php if (!empty($error_message)): ?>
            <p class="text-red-500 text-center mb-4"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="process/login_process.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium mb-1">Username</label>
                <input type="text" id="username" name="username" class="w-full p-2 rounded text-black" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-1">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 rounded text-black" required>
            </div>
            <!-- Google reCAPTCHA -->
            <div class="g-recaptcha mb-4" data-sitekey="<?php echo $siteKey; ?>"></div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold">
                Login
            </button>
        </form>
        <p class="text-center text-gray-400 mt-4">Don't have an account? <a href="signup.php" class="text-blue-400 underline">Sign Up</a></p>
    </div>
</body>
</html>
