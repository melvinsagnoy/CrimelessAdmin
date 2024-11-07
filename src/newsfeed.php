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

// Handle form submission
$message = '';
$messageType = ''; // Variable to store message type (success or error)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $image = ''; // Initialize image variable

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/post/";
        
        // Ensure the uploads/post directory exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Create the directory if it doesn't exist
        }

        // Sanitize the filename
        $imageFileName = basename($_FILES['image']['name']);
        $imageFileName = preg_replace("/[^a-zA-Z0-9.\-_]/", "", $imageFileName); // Remove dangerous characters
        $image = $targetDir . $imageFileName;

        // Check for allowed file types
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            $image = '';
            $message = "Invalid file type. Only JPG, PNG, and GIF types are allowed.";
            $messageType = 'error';
        } else {
            // Move the uploaded file
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
                $image = ''; // Reset image if upload fails
                $message = "Failed to upload image. Please check the uploads directory permissions.";
                $messageType = 'error';
            }
        }
    }

    // Insert into the news_feed table
    $sql = "INSERT INTO news_feed (title, content, image, likes, dislikes, created_at) VALUES (?, ?, ?, 0, 0, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $content, $image);

    if ($stmt->execute()) {
        $message = "News post added successfully!";
        $messageType = 'success';
    } else {
        $message = "Error: " . $stmt->error;
        $messageType = 'error';
    }

    $stmt->close();
    
    // Redirect to the same page to prevent form resubmission
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $messageType;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch existing news posts
$sql = "SELECT * FROM news_feed ORDER BY created_at DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Newsfeed</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome for like and dislike icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        .social-post {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            background-color: #fff;
        }
        .social-post img {
            width: 100%; /* Full width */
            height: 200px; /* Fixed height */
            object-fit: cover; /* Ensure the image maintains aspect ratio and fills the box */
            border-radius: 8px; /* Rounded corners to match the post */
        }
        .icons {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 10px;
        }
        .icon {
            display: flex;
            align-items: center;
            color: #6b7280; /* Gray color for icons */
        }
        .icon i {
            margin-right: 5px;
        }
    </style>
    <script>
        function openModal() {
            document.getElementById('postModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('postModal').style.display = 'none';
        }

        // Close the alert after 5 seconds
        window.addEventListener('DOMContentLoaded', (event) => {
            const alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 5000);
            }
        });
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex">
    <?php include '../components/sidebar.php'; ?> <!-- Sidebar for consistency -->

    <!-- Scrollable Content Area -->
    <div class="flex-1 p-6 overflow-y-auto max-h-screen">
        <h1 class="text-3xl font-bold mb-4">Admin Newsfeed</h1>

        <!-- Display success or error message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?php echo $_SESSION['message_type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> border-l-4 p-4 mb-4 rounded">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            </div>
        <?php endif; ?>

        <!-- Button to Open Modal -->
        <button onclick="openModal()" class="px-4 py-2 bg-blue-500 text-white rounded mb-6">Add Post</button>

        <!-- Modal Form -->
        <div id="postModal" class="modal flex">
            <div class="modal-content">
                <h2 class="text-2xl font-bold mb-4">Add a New Post</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 font-bold mb-2">Title:</label>
                        <input type="text" name="title" id="title" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="content" class="block text-gray-700 font-bold mb-2">Content:</label>
                        <textarea name="content" id="content" class="w-full px-4 py-2 border rounded" rows="5" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="image" class="block text-gray-700 font-bold mb-2">Image (optional):</label>
                        <input type="file" name="image" id="image" class="w-full px-4 py-2 border rounded">
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Post News</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Display Existing News Posts in Social Media Layout -->
        <div>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="social-post">
                        <h3 class="text-xl font-bold"><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="text-gray-700 mb-2"><?php echo htmlspecialchars($row['content']); ?></p>
                        <?php if ($row['image']): ?>
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Image" class="w-full h-auto rounded mb-2">
                        <?php endif; ?>
                        <div class="icons">
                            <div class="icon">
                                <i class="fas fa-thumbs-up"></i> <?php echo $row['likes']; ?>
                            </div>
                            <div class="icon">
                                <i class="fas fa-thumbs-down"></i> <?php echo $row['dislikes']; ?>
                            </div>
                        </div>
                        <p class="text-gray-500 text-sm"><?php echo htmlspecialchars($row['created_at']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-700">No news posts found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
