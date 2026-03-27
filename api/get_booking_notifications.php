<?php
header('Content-Type: application/json');
require 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => true,
        "notifications" => []
    ]);
    exit();
}

$userId = (int) $_SESSION['user_id'];

$sql = "SELECT id, status, pickup_location, destination, created_at, driver_name, driver_contact, cab_model, cab_number
        FROM bookings
        WHERE user_id = {$userId}
          AND status IN ('accepted', 'confirmed', 'completed')
        ORDER BY created_at DESC
        LIMIT 10";

$result = $conn->query($sql);
$notifications = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = [
            "id" => (int) $row['id'],
            "status" => $row['status'],
            "pickup_location" => $row['pickup_location'],
            "destination" => $row['destination'],
            "created_at" => $row['created_at'],
            "driver_name" => $row['driver_name'] ?: 'Assigned Driver',
            "driver_contact" => $row['driver_contact'] ?: 'N/A',
            "cab_model" => $row['cab_model'] ?: 'Assigned Cab',
            "cab_number" => $row['cab_number'] ?: '---'
        ];
    }
}

echo json_encode([
    "success" => true,
    "notifications" => $notifications
]);

$conn->close();
?>
