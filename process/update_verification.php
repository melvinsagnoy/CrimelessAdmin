<?php
include '../conn.php';
session_start();

// Get the JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['id']) || !isset($input['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

$id = intval($input['id']);
$status = $input['status'];

// Validate status
if (!in_array($status, ['Verified', 'Rejected'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit();
}

// Fetch the verify_id to log the image filename and check file existence
$query = $conn->prepare("SELECT verify_id FROM users WHERE id = ?");
$query->bind_param('i', $id);
$query->execute();
$queryResult = $query->get_result();

if ($queryResult->num_rows > 0) {
    $row = $queryResult->fetch_assoc();
    $verifyId = $row['verify_id'];

    // Log the retrieved verify_id
    error_log("Retrieved verify_id: $verifyId");

    // Path to the verification folder
    $verificationPath = "../uploads/verification/$verifyId";

    if (!empty($verifyId) && file_exists($verificationPath)) {
        error_log("File exists: $verificationPath");
    } else {
        error_log("File does not exist: $verificationPath");
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit();
}

// Update the user's verification status
$stmt = $conn->prepare("UPDATE users SET verify_status = ? WHERE id = ?");
$stmt->bind_param('si', $status, $id);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Verification status updated successfully',
        'image' => !empty($verifyId) && file_exists($verificationPath) ? $verificationPath : null,
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update verification status']);
}
?>
