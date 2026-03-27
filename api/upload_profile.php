<?php
require 'db_connect.php';
// session_start for user context and validation
session_start();
header('Content-Type: application/json');

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["success" => false, "message" => "Unauthorized"]));
}

// Process POST request with image file payload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $userId = $_SESSION['user_id'];
    $file = $_FILES['profile_image'];
    
    // MIME-type based validation for security
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        die(json_encode(["success" => false, "message" => "Only JPG, PNG, GIF allowed"]));
    }

    // Determine target storage directory using absolute paths
    $baseDir = dirname(__DIR__); 
    $targetDir = $baseDir . DIRECTORY_SEPARATOR . 'uploads';
    
    // Create 'uploads' folder dynamically if not exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Enforce unique naming convention for stored images to avoid collisions
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = "user_" . $userId . "_" . time() . "." . $ext;
    $targetPath = $targetDir . DIRECTORY_SEPARATOR . $filename;
    $dbPath = "uploads/" . $filename; 

    // Remove any previously stored profile pictures for the specific user
    $oldFiles = glob($targetDir . DIRECTORY_SEPARATOR . "user_" . $userId . "_*.*");
    if ($oldFiles) {
        foreach ($oldFiles as $oldFile) {
            @unlink($oldFile);
        }
    }

    // Finalize file transfer and update the user's database record
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $sql = "UPDATE users SET profile_image = '$dbPath' WHERE id = $userId";
        if ($conn->query($sql)) {
            $_SESSION['user_image'] = $dbPath;
            echo json_encode(["success" => true, "new_image" => $dbPath, "message" => "Upload successful"]);
        } else {
            echo json_encode(["success" => false, "message" => "SQL Error: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to move file. Ensure 'uploads' folder is writable."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request or missing file"]);
}
?>