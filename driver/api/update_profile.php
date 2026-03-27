<?php
session_start();
require '../../api/db_connect.php';

header('Content-Type: application/json');

error_reporting(0);
ini_set('display_errors', 0);

if (!isset($_SESSION['driver_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$driverId = $_SESSION['driver_id'];
$name = $conn->real_escape_string($_POST['name'] ?? '');
$email = $conn->real_escape_string($_POST['email'] ?? '');
$contact = $conn->real_escape_string($_POST['contact'] ?? '');

$updateQuery = "UPDATE drivers SET name = '$name', contact = '$contact'";

// Only update email if it was provided
if ($email) {
    $updateQuery .= ", email = '$email'";
}

// Handle image upload
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
    $targetDir = "../../assets/profiles/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileExt = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
    $fileName = "driver_" . $driverId . "_" . time() . "." . $fileExt;
    $targetFile = $targetDir . $fileName;
    
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
        $dbPath = "assets/profiles/" . $fileName;
        $updateQuery .= ", profile_image = '$dbPath'";
    }
}

$updateQuery .= " WHERE id = '$driverId'";

if ($conn->query($updateQuery)) {
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}
?>
