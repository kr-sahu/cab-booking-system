<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

header('Content-Type: application/json');
session_start();

try {
    require '../../api/db_connect.php';

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        throw new Exception('Email and password are required.');
    }

    if (!preg_match('/@gmail\.com$/i', $email)) {
        throw new Exception('Email must end with @gmail.com.');
    }

    $emailEscaped = $conn->real_escape_string($email);
    $result = $conn->query("SELECT * FROM drivers WHERE email = '$emailEscaped' LIMIT 1");

    if (!$result || $result->num_rows === 0) {
        throw new Exception('Account not found.');
    }

    $driver = $result->fetch_assoc();

    if ($driver['is_approved'] == 0) {
        throw new Exception('Your application is still pending approval.');
    }

    if ($driver['is_approved'] == 2) {
        throw new Exception('Your application was rejected. Contact support.');
    }

    if ($driver['is_active'] == 0) {
        throw new Exception('Your account is deactivated. Please contact support.');
    }

    if (!password_verify($password, $driver['password'])) {
        throw new Exception('Invalid password.');
    }

    $_SESSION['driver_id'] = $driver['id'];
    $_SESSION['driver_name'] = $driver['name'];

    ob_clean();
    echo json_encode([
        'success' => true,
        'message' => 'Login successful.',
        'redirect' => 'index.php'
    ]);
} catch (Exception $e) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
