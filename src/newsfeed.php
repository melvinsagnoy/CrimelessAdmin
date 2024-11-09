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

// Handle form submission for adding a post
$message = '';
$messageType = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_post'])) {
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $image = ''; // Initialize image variable

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/post/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $imageFileName = preg_replace("/[^a-zA-Z0-9.\-_]/", "", basename($_FILES['image']['name']));
        $image = $targetDir . $imageFileName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            $image = '';
            $message = "Invalid file type. Only JPG, PNG, and GIF types are allowed.";
            $messageType = 'error';
        } else {
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
                $image = '';
                $message = "Failed to upload image.";
                $messageType = 'error';
            }
        }
    }

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
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $messageType;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Edit and Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM news_feed WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Post deleted successfully!";
    $_SESSION['message_type'] = 'success';
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_post'])) {
    $id = intval($_POST['id']);
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $image = '';

    // Check if a new image was uploaded
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/post/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $imageFileName = preg_replace("/[^a-zA-Z0-9.\-_]/", "", basename($_FILES['image']['name']));
        $image = $targetDir . $imageFileName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
                // Image uploaded successfully
            } else {
                $image = ''; // Reset image if upload fails
            }
        }
    }

    // Update query with or without a new image
    if ($image) {
        $sql = "UPDATE news_feed SET title = ?, content = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $content, $image, $id);
    } else {
        $sql = "UPDATE news_feed SET title = ?, content = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $id);
    }

    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Post updated successfully!";
    $_SESSION['message_type'] = 'success';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        /* Custom Styles */
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            z-index: 50;
        }
        .modal.show {
            display: flex;
        }
        .modal-content {
            background: white;
            padding: 24px;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        .modal-content h2 {
            color: #1f2937;
            font-weight: bold;
            margin-bottom: 12px;
        }
        .social-post {
            background: #ffffff;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .social-post:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }
        .button-primary {
        background: white; /* A nice blue color */
        color: black;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: bold;
        transition: background 0.3s ease, transform 0.2s ease;
        }
        .button-primary:hover {
            background: white; /* A slightly darker blue */
            transform: translateY(-2px);
        }
        .button-secondary {
            background: #6b7280; /* Neutral gray color */
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .button-secondary:hover {
            background: #4b5563; /* A darker gray */
            transform: translateY(-2px);
        }
        .three-dots {
            position: absolute;
            top: 12px;
            right: 12px;
            cursor: pointer;
            color: #6b7280;
            font-size: 18px;
            transition: color 0.2s;
        }
        .three-dots:hover {
            color: #4b5563;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 40px;
            right: 12px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }
        .dropdown-menu a {
            display: block;
            padding: 8px 16px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }
        .icons {
            display: flex;
            gap: 12px;
            margin-top: 8px;
            font-size: 14px;
            color: #6b7280;
        }
        .icon {
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: color 0.2s;
        }
        .icon:hover {
            color: #1d4ed8;
        }
        .fixed-image {
            width: 100%;
            height: 180px; /* Adjusted height for a consistent image size */
            object-fit: cover;
            border-radius: 8px;
        }
        .lottie-large {
            width: 100px; /* Adjust width */
            height: 100px; /* Adjust height */
        }
    </style>
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('show');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }

    window.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        });
    });

    function toggleDropdown(id) {
        const dropdown = document.getElementById('dropdown-' + id);
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }


    // Function to open the modal and set initial data
    function openEditModal(id, title, content, image) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_content').value = content;
        document.getElementById('edit_image_preview').src = image || '';
        openModal('editModal');
    }



</script>


</head>
<body class="bg-gray-100 min-h-screen flex">
    <?php include '../components/sidebar.php'; ?>
    <div class="flex-1 p-6 overflow-y-auto max-h-screen">
        <h1 class="text-3xl font-bold mb-4">Admin Newsfeed</h1>

        <!-- Display Messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['message_type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> border-l-4 p-4 mb-4 rounded">
                <?= $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
            </div>
        <?php endif; ?>

        <!-- Add Post Button -->
        <button onclick="openModal('postModal')" class="button-primary shadow-md mb-6">Add Post</button>

                <!-- Newsfeed -->
                <div>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="social-post">
                <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($row['title']); ?></h3>
                <p class="text-gray-700 mb-2"><?= htmlspecialchars($row['content']); ?></p>
                
                <?php if ($row['image']): ?>
                    <!-- Add the onclick event to the image only -->
                    <img src="<?= htmlspecialchars($row['image']); ?>" 
                        alt="Image" 
                        class="fixed-image mb-2" 
                        onclick="openContentPreview(
                            '<?= htmlspecialchars(addslashes($row['title'])); ?>',
                            '<?= htmlspecialchars(addslashes($row['content'])); ?>',
                            '<?= htmlspecialchars($row['image']); ?>',
                            '<?= $row['likes']; ?>',
                            '<?= $row['dislikes']; ?>',
                            '<?= htmlspecialchars($row['created_at']); ?>'
                        )">
                <?php endif; ?>
                
                <div class="icons">
                    <div class="icon"><i class="fas fa-thumbs-up"></i> <?= $row['likes']; ?></div>
                    <div class="icon"><i class="fas fa-thumbs-down"></i> <?= $row['dislikes']; ?></div>
                </div>
                <div class="three-dots" onclick="toggleDropdown(<?= $row['id']; ?>)">⋮</div>
                <div id="dropdown-<?= $row['id']; ?>" class="dropdown-menu">
                    <a href="?delete=<?= $row['id']; ?>" class="text-red-500">Delete</a>
                    <a href="#" onclick="event.stopPropagation(); openEditModal(
                        <?= $row['id']; ?>,
                        '<?= addslashes(htmlspecialchars($row['title'])); ?>',
                        '<?= addslashes(htmlspecialchars($row['content'])); ?>',
                        '<?= addslashes(htmlspecialchars($row['image'])); ?>'
                    )" class="text-blue-500">Edit</a>
                </div>
                <p class="text-gray-500 text-sm mt-2"><?= htmlspecialchars($row['created_at']); ?></p>
            </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-700">No news posts found.</p>
            <?php endif; ?>
        </div>
    </div>


    <!-- Add Post Modal -->
    <div id="postModal" class="modal">
        <div class="modal-content">
            <h2 class="text-2xl font-bold mb-4">Add a New Post</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="add_post" value="1">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-bold mb-2">Title:</label>
                    <input type="text" name="title" id="title" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500 transition" required>
                </div>
                <div class="mb-4">
                    <label for="content" class="block text-gray-700 font-bold mb-2">Content:</label>
                    <textarea name="content" id="content" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500 transition" rows="4" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Image (optional):</label>
                    <img id="add_image_preview" src="" alt="Image Preview" class="w-full h-auto rounded mb-2" style="display: none;">
                    <input type="file" name="image" id="image" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit" class="button-primary flex items-center">
                    <div id="lottie-upload" class="mr-2 lottie-large"></div> <!-- Lottie animation -->
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2 class="text-2xl font-bold mb-4">Edit Post</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="edit_post" value="1">
                <input type="hidden" id="edit_id" name="id">
                <div class="mb-4">
                    <label for="edit_title" class="block text-gray-700 font-bold mb-2">Title:</label>
                    <input type="text" id="edit_title" name="title" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500 transition" required>
                </div>
                <div class="mb-4">
                    <label for="edit_content" class="block text-gray-700 font-bold mb-2">Content:</label>
                    <textarea id="edit_content" name="content" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500 transition" rows="4" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Current Image:</label>
                    <img id="edit_image_preview" src="" alt="Current Image" class="w-full h-auto rounded mb-2">
                    <label for="edit_image" class="block text-gray-700 font-bold mb-2">Change Image (optional):</label>
                    <input type="file" name="image" id="edit_image" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div class="flex justify-end space-x-2">
                <button type="submit" class="button-primary flex items-center">
                    <div id="lottie-upload-edit" class="mr-2 lottie-large"></div> <!-- Lottie animation for Edit Post -->
                </button>
            </div>
            </form>
        </div>
    </div>

      <!-- Content Preview Modal -->
    <div id="contentPreviewModal" class="modal">
        <div class="modal-content">
            <h2 id="preview_title" class="text-2xl font-bold mb-4"></h2>
            <p id="preview_content" class="text-gray-700 mb-4"></p>
            <img id="preview_image" src="" alt="Image Preview" class="w-full h-auto rounded mb-4" style="display: none;">
            <div class="icons mb-4">
                <div class="icon"><i class="fas fa-thumbs-up"></i> <span id="preview_likes"></span></div>
                <div class="icon"><i class="fas fa-thumbs-down"></i> <span id="preview_dislikes"></span></div>
            </div>
            <p id="preview_date" class="text-gray-500 text-sm"></p>
            <div class="flex justify-end">
                <button onclick="closeModal('contentPreviewModal')" class="button-secondary">Close</button>
            </div>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.5/lottie.min.js"></script>

<!-- Your custom JavaScript code that uses Lottie -->
<script>
    // Your JavaScript code
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('add_image_preview');
                preview.src = e.target.result; // Set the preview image source
                preview.style.display = 'block'; // Make the image preview visible
            };
            reader.readAsDataURL(file); // Read the file and convert it to a data URL
        }
    });

        // Initialize the Lottie animation for Add Post Modal
    lottie.loadAnimation({
        container: document.getElementById('lottie-upload'), // The container element
        renderer: 'svg',
        loop: true, // Set to 'true' for continuous looping
        autoplay: true, // Automatically start the animation
        path: '../assets/lottie/post.json' // Replace with the path to your downloaded JSON file
    });

    // Initialize the Lottie animation for Edit Post Modal
    lottie.loadAnimation({
        container: document.getElementById('lottie-upload-edit'), // The container element for Edit Post
        renderer: 'svg',
        loop: true, // Set to 'true' for continuous looping
        autoplay: true, // Automatically start the animation
        path: '../assets/lottie/post.json' // Same animation path
    });

    // Event listener to update the image preview when a new image is selected
    document.getElementById('edit_image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Update the image preview with the new selected image
                document.getElementById('edit_image_preview').src = e.target.result;
            };
            reader.readAsDataURL(file); // Read the file and convert it to a data URL
        }
    });

    function openContentPreview(title, content, image, likes, dislikes, date) {
        document.getElementById('preview_title').innerText = title;
        document.getElementById('preview_content').innerText = content;
        document.getElementById('preview_likes').innerText = likes;
        document.getElementById('preview_dislikes').innerText = dislikes;
        document.getElementById('preview_date').innerText = date;
        if (image) {
            document.getElementById('preview_image').src = image;
            document.getElementById('preview_image').style.display = 'block';
        } else {
            document.getElementById('preview_image').style.display = 'none';
        }
        openModal('contentPreviewModal');
    }
</script>
</html>