<?php
header('Content-Type: application/json');
require 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die(json_encode(["success" => false, "message" => "Unauthorized"]));
}

$uid = $_SESSION['user_id'];
$response = ["success" => false, "message" => "Unknown error"];

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $conn->real_escape_string($_POST['fullname'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $gender = $conn->real_escape_string($_POST['gender'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    
    $newPass = $_POST['new_password'] ?? '';
    $currPass = $_POST['current_password'] ?? '';

    // Basic Validation
    if (empty($fullname) || empty($email)) {
        die(json_encode(["success" => false, "message" => "Name and Email are required"]));
    }

    // Fetch current user data for verification
    $userRes = $conn->query("SELECT * FROM users WHERE id = $uid");
    $userData = $userRes->fetch_assoc();

    // Password Update Logic
    if (!empty($newPass)) {
        if (empty($currPass)) {
            die(json_encode(["success" => false, "message" => "Current password is required to change password"]));
        }
        if (!password_verify($currPass, $userData['password'])) {
            die(json_encode(["success" => false, "message" => "Current password incorrect"]));
        }
        $hashedPass = password_hash($newPass, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password = '$hashedPass' WHERE id = $uid");
    }

    // Avatar Upload Logic
    $avatarPath = $userData['profile_image'];
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $fileName = 'user_' . $uid . '_' . time() . '.' . $ext;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            $avatarPath = 'uploads/' . $fileName;
        }
    }

    // Final Update
    $updateSql = "UPDATE users SET 
        fullname = '$fullname', 
        phone = '$phone', 
        gender = '$gender',
        email = '$email',
        profile_image = '$avatarPath'
        WHERE id = $uid";

    if ($conn->query($updateSql)) {
        $_SESSION['user_name'] = $fullname;
        $response = ["success" => true, "message" => "Account updated successfully"];
    } else {
        $response = ["success" => false, "message" => "Database error: " . $conn->error];
    }
}

echo json_encode($response);
$conn->close();
?>
