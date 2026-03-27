<?php
header('Content-Type: application/json');
require '../../api/db_connect.php';
session_start();

if (!isset($_SESSION['driver_id'])) {
    die(json_encode(["success" => false, "message" => "Unauthorized"]));
}

$data = json_decode(file_get_contents('php://input'), true);
$driverId = $_SESSION['driver_id'];

if (isset($data['status'])) {
    $status = $conn->real_escape_string($data['status']);
    
    // Safety check: only allow 'available' or 'offline'
    if(!in_array($status, ['available', 'offline'])) {
        die(json_encode(["success" => false, "message" => "Invalid status"]));
    }

    $sql = "UPDATE drivers SET status = '$status' WHERE id = '$driverId'";
    
    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "status" => $status]);
    } else {
        echo json_encode(["success" => false, "message" => $conn->error]);
    }
} else {
    // Return current status
    $res = $conn->query("SELECT status FROM drivers WHERE id = '$driverId'");
    $row = $res->fetch_assoc();
    echo json_encode(["success" => true, "status" => $row['status']]);
}

$conn->close();
?>
