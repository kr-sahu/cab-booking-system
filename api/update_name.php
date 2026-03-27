<?php
session_start();
include 'db_connect.php';

// Retrieve new username from JSON request payload
$data = json_decode(file_get_contents('php://input'), true);
$new_name = trim($data['name'] ?? '');

// Validation: Ensure name is not empty and user is authenticated
if (empty($new_name) || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Update the user's name in the database using a prepared statement for security
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("UPDATE users SET fullname = ? WHERE id = ?");
$stmt->bind_param("si", $new_name, $user_id);

if ($stmt->execute()) {
    // Sync the updated name with the current session state
    $_SESSION['user_name'] = $new_name;
    echo json_encode(['success' => true, 'name' => $new_name]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
