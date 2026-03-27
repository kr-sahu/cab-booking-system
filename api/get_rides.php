<?php
header('Content-Type: application/json');
require 'db_connect.php';
// Re-initialize session to access authenticated user context
session_start();

// Authorization Guard: Check if a user is currently logged in
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["success" => false, "message" => "Unauthorized"]));
}

// Fetch ride historical data for the logged-in user, ordered by most recent
$userId = $_SESSION['user_id'];
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 0;
$limitSql = $limit > 0 ? " LIMIT $limit" : "";

$sql = "SELECT id, pickup_location, destination, fare, distance, status, created_at, driver_name, driver_contact, cab_model, cab_number 
        FROM bookings 
        WHERE user_id = '$userId' 
        ORDER BY created_at DESC" . $limitSql;

$result = $conn->query($sql);
$rides = [];

// Aggregate query results into an associative array for JSON output
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
