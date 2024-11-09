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


include '../option_modal/option_modal.php';
// Handle form submission for adding a question
$message = '';
$messageType = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_question'])) {
    $question = htmlspecialchars($_POST['question']);
    $option_a = htmlspecialchars($_POST['option_a']);
    $option_b = htmlspecialchars($_POST['option_b']);
    $option_c = htmlspecialchars($_POST['option_c']);
    $option_d = htmlspecialchars($_POST['option_d']);
    $correct_option = htmlspecialchars($_POST['correct_option']);
    $points = intval($_POST['points']);

    $sql = "INSERT INTO game (question, option_a, option_b, option_c, option_d, correct_option, points, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $question, $option_a, $option_b, $option_c, $option_d, $correct_option, $points);

    if ($stmt->execute()) {
        $message = "Question added successfully!";
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

// Handle editing a question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_question'])) {
    $question_id = $_POST['question_id'];
    $question = htmlspecialchars($_POST['question']);
    $option_a = htmlspecialchars($_POST['option_a']);
    $option_b = htmlspecialchars($_POST['option_b']);
    $option_c = htmlspecialchars($_POST['option_c']);
    $option_d = htmlspecialchars($_POST['option_d']);
    $correct_option = htmlspecialchars($_POST['correct_option']);
    $points = intval($_POST['points']);

    $sql = "UPDATE game SET question=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_option=?, points=?, updated_at=NOW() WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssii", $question, $option_a, $option_b, $option_c, $option_d, $correct_option, $points, $question_id);

    if ($stmt->execute()) {
        $message = "Question updated successfully!";
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

// Handle deleting a question
if (isset($_GET['delete'])) {
    $question_id = $_GET['delete'];

    $sql = "DELETE FROM game WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $question_id);

    if ($stmt->execute()) {
        $message = "Question deleted successfully!";
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

// Fetch existing questions
$sql = "SELECT * FROM game ORDER BY created_at DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Game Questions</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        /* Custom Styles for Beautification */
        /* Custom Styles for Beautification */
body {
    background-color: #f3f4f6;
    font-family: 'Inter', sans-serif;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    display: flex;
    gap: 20px;
}

.header {
    background-color: #1f2937;
    color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
    position: sticky;
    top: 0; /* Make the header sticky */
    z-index: 10; /* Ensure the header stays above the content */
}

.add-question-container {
    display: flex;
    justify-content: center;
    margin-bottom: 6px;
    position: sticky;
    top: 70px; /* Adjust the position of the button to stay below the header */
    z-index: 9; /* Ensure the button stays below the header */
}

.main-content {
    flex: 3;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    height: 80vh; /* Set a height for the scrollable container */
    overflow-y: auto; /* Allow scrolling for the questions */
}

/* Sidebar Styles */
.right-sidebar {
    flex: 1;
    background-color: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    min-height: auto;
    height: auto;
}

/* Sidebar Option Styles */
.sidebar-option {
    display: flex;
    align-items: center;
    margin-bottom: 16px;
    font-weight: bold;
    color: #1f2937;
    cursor: pointer;
    transition: color 0.3s;
}

.sidebar-option:hover {
    color: #3b82f6;
}

.sidebar-option i {
    font-size: 1.25rem;
    margin-right: 8px;
    color: #6b7280;
    transition: color 0.3s;
}

.sidebar-option:hover i {
    color: #3b82f6;
}
.header {
    background-color: #1f2937;
    color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
}

.button-primary {
    background-color: #3b82f6;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: bold;
    transition: background 0.3s ease, transform 0.2s ease;
}

.button-primary:hover {
    background-color: #2563eb;
    transform: translateY(-2px);
}

.button-secondary {
    background-color: #9ca3af;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    transition: background 0.3s ease, transform 0.2s ease;
}

.button-secondary:hover {
    background-color: #6b7280;
    transform: translateY(-2px);
}

.social-post {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    position: relative;
    max-height: 350px; /* Set a fixed height for the post */
    overflow-y: auto; /* Make the content scrollable if it overflows */
    margin-top: auto; /* Push the post to the bottom of the screen */
}

.icon-container {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex; /* Align icons horizontally */
    gap: 10px; /* Adds space between the icons */
    align-items: center; /* Ensures the icons are vertically centered */
}

.icon {
    cursor: pointer;
    color: #6b7280;
    transition: color 0.2s;
}

.icon.fa-edit:hover {
    color: #10b981; /* Green color for Edit */
}

.icon.fa-trash:hover {
    color: #ef4444; /* Red color for Delete */
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
    background-color: white;
    padding: 30px;
    border-radius: 15px;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
}

.form-input, .form-textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.form-input:focus, .form-textarea:focus {
    border-color: #3b82f6;
    outline: none;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert.success {
    background-color: #d1fae5;
    color: #065f46;
}

.alert.error {
    background-color: #fee2e2;
    color: #991b1b;
}
    </style>
    <script>
        let animation;

function confirmDelete(questionId) {
    // Show the confirmation modal
    document.getElementById('deleteConfirmationModal').classList.add('show');
    
    // Stop any existing Lottie animation
    if (animation) {
        animation.destroy(); // Destroy previous instance
    }

    // Initialize Lottie animation
    animation = lottie.loadAnimation({
        container: document.getElementById('lottie-animation'), // Specify the container
        renderer: 'svg', // Animation renderer
        loop: true, // Set to loop
        autoplay: true, // Set to autoplay when modal is displayed
        path: '../assets/lottie/delete.json' // Path to the downloaded Lottie JSON file
    });

    // Assign the delete action to the button
    document.getElementById('deleteConfirmationButton').onclick = function() {
        window.location.href = "?delete=" + questionId;
    };
}

function closeDeleteModal() {
    // Close the modal
    document.getElementById('deleteConfirmationModal').classList.remove('show');
    
    // Destroy the Lottie animation when closing the modal
    if (animation) {
        animation.destroy(); // Destroy the Lottie animation instance
    }
}

window.addEventListener('click', function(event) {
    const modal = document.getElementById('editQuestionModal');
    if (event.target === modal) {
        closeModal();
    }

    const deleteModal = document.getElementById('deleteConfirmationModal');
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
});

function openModal(questionId, question, optionA, optionB, optionC, optionD, correctOption, points) {
    document.getElementById('editQuestionModal').classList.add('show');
    document.getElementById('question_id').value = questionId;
    document.getElementById('edit_question').value = question;
    document.getElementById('edit_option_a').value = optionA;
    document.getElementById('edit_option_b').value = optionB;
    document.getElementById('edit_option_c').value = optionC;
    document.getElementById('edit_option_d').value = optionD;
    document.getElementById('edit_correct_option').value = correctOption;
    document.getElementById('edit_points').value = points;
}

function closeModal() {
    document.getElementById('editQuestionModal').classList.remove('show');
}



    </script>
</head>
<body class="bg-gray-100 min-h-screen flex">
    <?php include '../components/sidebar.php'; ?> <!-- Include Sidebar -->

    <div class="container">
        <div class="main-content">
            <div class="header">
                Admin Game Questions
            </div>

            <!-- Display Messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert <?= $_SESSION['message_type'] === 'success' ? 'success' : 'error'; ?>">
                    <?= $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
                </div>
            <?php endif; ?>

        <!-- Add Question Button -->
        <div class="add-question-container">
            <button id="addQuestionButton" class="button-primary">Add Question</button>
        </div>

            <!-- Questions List -->
            <div>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="social-post">
                            <div class="icon-container">
                                <i class="fas fa-edit icon" title="Edit" onclick="openModal(<?= $row['id']; ?>, '<?= addslashes($row['question']); ?>', '<?= addslashes($row['option_a']); ?>', '<?= addslashes($row['option_b']); ?>', '<?= addslashes($row['option_c']); ?>', '<?= addslashes($row['option_d']); ?>', '<?= addslashes($row['correct_option']); ?>', <?= $row['points']; ?>)"></i>
                                <a href="javascript:void(0);" onclick="confirmDelete(<?= $row['id']; ?>)">
                                    <i class="fas fa-trash icon" title="Delete"></i>
                                </a>
                            </div><br>
                            <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($row['question']); ?></h3>
                            <p class="text-gray-700">A: <?= htmlspecialchars($row['option_a']); ?></p>
                            <p class="text-gray-700">B: <?= htmlspecialchars($row['option_b']); ?></p>
                            <p class="text-gray-700">C: <?= htmlspecialchars($row['option_c']); ?></p>
                            <p class="text-gray-700">D: <?= htmlspecialchars($row['option_d']); ?></p>
                            <p class="text-gray-500 text-sm mt-2">Correct Option: <?= htmlspecialchars($row['correct_option']); ?></p>
                            <p class="text-gray-500 text-sm">Points: <?= $row['points']; ?></p>
                            <p class="text-gray-500 text-sm">Created At: <?= htmlspecialchars($row['created_at']); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-gray-700 text-center">No questions found. Add some to get started!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Add Question Modal -->
        <div id="addQuestionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Add New Question</div>
        <form method="POST">
            <input type="hidden" name="add_question" value="1">
            <!-- Question Input -->
            <div class="mb-4">
                <label for="question" class="block text-gray-700 font-bold mb-2">Question:</label>
                <textarea name="question" id="question" class="form-textarea" rows="4" required></textarea>
            </div>

            <!-- Option A -->
            <div class="mb-4">
                <label for="option_a" class="block text-gray-700 font-bold mb-2">Option A:</label>
                <input type="text" name="option_a" id="option_a" class="form-input" required>
            </div>

            <!-- Option B -->
            <div class="mb-4">
                <label for="option_b" class="block text-gray-700 font-bold mb-2">Option B:</label>
                <input type="text" name="option_b" id="option_b" class="form-input" required>
            </div>

            <!-- Option C -->
            <div class="mb-4">
                <label for="option_c" class="block text-gray-700 font-bold mb-2">Option C:</label>
                <input type="text" name="option_c" id="option_c" class="form-input" required>
            </div>

            <!-- Option D -->
            <div class="mb-4">
                <label for="option_d" class="block text-gray-700 font-bold mb-2">Option D:</label>
                <input type="text" name="option_d" id="option_d" class="form-input" required>
            </div>

            <!-- Correct Option -->
            <div class="mb-4">
                <label for="correct_option" class="block text-gray-700 font-bold mb-2">Correct Option (A, B, C, D):</label>
                <input type="text" name="correct_option" id="correct_option" class="form-input" required>
            </div>

            <!-- Points Input -->
            <div class="mb-4">
                <label for="points" class="block text-gray-700 font-bold mb-2">Points:</label>
                <input type="number" name="points" id="points" class="form-input" required>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="submit" class="button-primary">Add Question</button>
                <button type="button" onclick="closeAddQuestionModal()" class="button-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div class="right-sidebar">
    <h2 class="text-xl font-bold mb-4">Options</h2>

        <div class="sidebar-option" onclick="openModal('trackScores')">
        <div class="flex items-center">
            <div id="score-animation" style="width: 40px; height: 40px; margin-right: 8px;"></div>
            Track Scores
        </div>
    </div>

    <div class="sidebar-option" onclick="openModal('trackProgress')">
        <div class="flex items-center">
            <div id="progress-animation" style="width: 40px; height: 40px; margin-right: 8px;"></div>
            Track Progress
        </div>
    </div>

    <div class="sidebar-option" onclick="openModal('leaderboard')">
        <div class="flex items-center">
            <div id="leaderboard-animation" style="width: 40px; height: 40px; margin-right: 8px;"></div>
            Leaderboard
        </div>
    </div>

    <div class="sidebar-option" onclick="openModal('rewards')">
        <div class="flex items-center">
            <div id="reward-animation" style="width: 40px; height: 40px; margin-right: 8px;"></div>
            Rewards
        </div>
    </div>
</div>


    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Confirm Deletion</div>
            <p>Are you sure you want to delete this question?</p>
            
            <!-- Lottie Animation -->
            <div id="lottie-animation" style="width: 100%; height: 150px; margin: 20px auto;"></div>
            
            <div class="flex justify-end space-x-4">
                <button type="button" id="deleteConfirmationButton" class="button-primary">Yes, Delete</button>
                <button type="button" onclick="closeDeleteModal()" class="button-secondary">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Edit Question Modal -->
    <div id="editQuestionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Edit Question</div>
            <form method="POST">
                <input type="hidden" name="edit_question" value="1">
                <input type="hidden" id="question_id" name="question_id">
                <div class="mb-4">
                    <label for="edit_question" class="block text-gray-700 font-bold mb-2">Question:</label>
                    <textarea name="question" id="edit_question" class="form-textarea" rows="4" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="edit_option_a" class="block text-gray-700 font-bold mb-2">Option A:</label>
                    <input type="text" name="option_a" id="edit_option_a" class="form-input" required>
                </div>
                <div class="mb-4">
                    <label for="edit_option_b" class="block text-gray-700 font-bold mb-2">Option B:</label>
                    <input type="text" name="option_b" id="edit_option_b" class="form-input" required>
                </div>
                <div class="mb-4">
                    <label for="edit_option_c" class="block text-gray-700 font-bold mb-2">Option C:</label>
                    <input type="text" name="option_c" id="edit_option_c" class="form-input" required>
                </div>
                <div class="mb-4">
                    <label for="edit_option_d" class="block text-gray-700 font-bold mb-2">Option D:</label>
                    <input type="text" name="option_d" id="edit_option_d" class="form-input" required>
                </div>
                <div class="mb-4">
                    <label for="edit_correct_option" class="block text-gray-700 font-bold mb-2">Correct Option (A, B, C, D):</label>
                    <input type="text" name="correct_option" id="edit_correct_option" class="form-input" required>
                </div>
                <div class="mb-4">
                    <label for="edit_points" class="block text-gray-700 font-bold mb-2">Points:</label>
                    <input type="number" name="points" id="edit_points" class="form-input" required>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="submit" class="button-primary">Update Question</button>
                    <button type="button" onclick="closeModal()" class="button-secondary">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.8/lottie.min.js"></script>
<script>
    // Lottie Initialization
    lottie.loadAnimation({
        container: document.getElementById('score-animation'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '../assets/lottie/score.json' // Path to your Lottie JSON animation
    });

    lottie.loadAnimation({
        container: document.getElementById('progress-animation'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '../assets/lottie/progress.json'
    });

    lottie.loadAnimation({
        container: document.getElementById('leaderboard-animation'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '../assets/lottie/leaderboard.json'
    });

    lottie.loadAnimation({
        container: document.getElementById('reward-animation'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '../assets/lottie/reward.json'
    });

    // Function to open the Add Question modal
function openAddQuestionModal() {
    document.getElementById('addQuestionModal').classList.add('show');  // Add the show class to display the modal
}

// Function to close the Add Question modal
function closeAddQuestionModal() {
    document.getElementById('addQuestionModal').classList.remove('show');  // Remove the show class to hide the modal
}

// Add Event Listener for the 'Add Question' button
document.getElementById('addQuestionButton').addEventListener('click', openAddQuestionModal);

// Close modal when clicked outside the modal
window.addEventListener('click', function(event) {
    const modal = document.getElementById('addQuestionModal');
    if (event.target === modal) {
        closeAddQuestionModal();  // Close modal if clicked outside
    }
});


// Function to open a modal
function openModal(modalId) {
    // Close all modals first
    closeAllModals();

    // Show the selected modal
    const modal = document.getElementById(modalId + 'Modal'); // Make sure to append 'Modal'
    if (modal) {
        modal.classList.add("show");
    } else {
        console.error('Modal with ID ' + modalId + 'Modal not found.');
    }
}

// Function to close a specific modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
    } else {
        console.error('Modal with ID ' + modalId + ' not found.');
    }
}

// Function to close all modals
function closeAllModals() {
    let modals = document.querySelectorAll('.modal');
    modals.forEach(modal => modal.classList.remove('show'));
}

// Close modal by clicking outside
window.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        if (event.target === modal) {
            closeModal(modal.id); // Close modal if clicked outside
        }
    });
});

</script>
</html>