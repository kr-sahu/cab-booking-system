<?php
header('Content-Type: application/json');
require 'db_connect.php';
session_start();

// Guard access for signed-in users only.
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["success" => false, "message" => "Unauthorized"]));
}

$userId = $_SESSION['user_id'];
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 0;
$limitSql = $limit > 0 ? " LIMIT $limit" : "";

$sql = "SELECT id,
               pickup_location,
               destination,
               fare,
               distance,
               CASE WHEN status = 'accepted' THEN 'confirmed' ELSE status END AS status,
               created_at,
               driver_name,
               driver_contact,
               cab_model,
               cab_number 
        FROM bookings 
        WHERE user_id = '$userId' 
        ORDER BY created_at DESC" . $limitSql;

$result = $conn->query($sql);
$rides = [];

// Build the ride response list in query order.
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rides[] = $row;
    }
    echo json_encode(["success" => true, "rides" => $rides]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
}

$conn->close();
?>
