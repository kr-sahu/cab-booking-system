<?php
header('Content-Type: application/json');
require '../../api/db_connect.php';
session_start();

if (!isset($_SESSION['driver_id'])) {
    die(json_encode(["success" => false, "message" => "Unauthorized"]));
}

$driverId = $_SESSION['driver_id'];

// 1. Get driver's current active trip if any
$activeTripRes = $conn->query("SELECT b.*, u.fullname as passenger_name, u.phone as passenger_phone 
                               FROM bookings b 
                               LEFT JOIN users u ON b.user_id = u.id 
                               WHERE b.driver_id = '$driverId' AND b.status = 'confirmed' LIMIT 1");

$activeTrip = $activeTripRes->num_rows > 0 ? $activeTripRes->fetch_assoc() : null;

// 2. Get pending bookings available for ANY driver
$sql = "SELECT b.*, u.fullname as passenger_name, u.phone as passenger_phone 
        FROM bookings b 
        LEFT JOIN users u ON b.user_id = u.id 
        WHERE b.status = 'pending' 
        ORDER BY b.created_at DESC";

$result = $conn->query($sql);
$bookings = [];
while($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

// 3. Get driver's recent completed trips
$recentSql = "SELECT b.*, u.fullname as passenger_name 
              FROM bookings b 
              LEFT JOIN users u ON b.user_id = u.id 
              WHERE b.driver_id = '$driverId' AND b.status = 'completed' 
              ORDER BY b.created_at DESC LIMIT 5";
$recentRes = $conn->query($recentSql);
$recentTrips = [];
while($row = $recentRes->fetch_assoc()) {
    $recentTrips[] = $row;
}

echo json_encode([
    "success" => true, 
    "active_trip" => $activeTrip,
    "bookings" => $bookings,
    "recent_trips" => $recentTrips
]);

$conn->close();
?>
