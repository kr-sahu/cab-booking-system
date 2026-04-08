<?php
session_start();
require_once 'config/db.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 1. Check if the user is a System Administrator
    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $username;
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid admin password.";
        }
    } else {
        // 2. Check if the user is an Approved Driver Application
        $stmt_driver = $conn->prepare("SELECT id, name, password, is_approved, is_active FROM drivers WHERE email = ?");
        $stmt_driver->bind_param("s", $username);
        $stmt_driver->execute();
        $res_driver = $stmt_driver->get_result();
        
        if ($driver = $res_driver->fetch_assoc()) {
             if (password_verify($password, $driver['password'])) {
                 if ($driver['is_approved'] == 0) {
                     $error = "Your driver application is still pending approval.";
                 } elseif ($driver['is_approved'] == 2) {
                     $error = "Your driver application was rejected. Please contact support.";
                 } elseif ($driver['is_active'] == 0) {
                     $error = "Your account is currently inactive.";
                 } else {
                     // Login successful for Driver
                     $_SESSION['driver_id'] = $driver['id'];
                     $_SESSION['driver_name'] = $driver['name'];
                     header('Location: ../driver/index.php'); 
                     exit;
                 }
             } else {
                 $error = "Invalid password for this account.";
             }
        } else {
            $error = "Account not found in our records.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Cab Booking Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-panel">
            <div class="login-aside">
                <span class="login-badge">Control Center</span>
                <h1>Admin Panel</h1>
                <p>Manage bookings, users, and daily operations from one secure place.</p>
            </div>

            <div class="login-form-area">
                <div class="login-header">
                    <h2>Welcome back</h2>
                    <p>Please login to your account.</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter admin username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter password" required>
                    </div>
                    <button type="submit" class="login-btn">Sign In</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
