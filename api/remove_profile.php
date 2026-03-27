<?php
require 'db_connect.php';
session_start();
header('Content-Type: application/json');

// Check if user is authenticated before allowing image removal
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["success" => false, "message" => "Unauthorized"]));
}

$userId = $_SESSION['user_id'];

// Retrieve and delete the actual image file from the server storage
$res = $conn->query("SELECT profile_image FROM users WHERE id = $userId");
$row = $res->fetch_assoc();

if ($row && !empty($row['profile_image'])) {
    $filePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $row['profile_image']);
    if (file_exists($filePath)) {
        @unlink($filePath);
    }
}

// Clear the profile image path in the users table database
$sql = "UPDATE users SET profile_image = NULL WHERE id = $userId";

if ($conn->query($sql)) {
    $_SESSION['user_image'] = null;
    $defaultImage = 'https://cdn-icons-png.flaticon.com/512/847/847969.png';
    // Return the default icon URL so the UI can update immediately
    echo json_encode(["success" => true, "default_image" => $defaultImage, "message" => "Profile picture removed"]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
}
?>
