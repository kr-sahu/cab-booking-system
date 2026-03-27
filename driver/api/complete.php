<?php
header('Content-Type: application/json');
require '../../api/db_connect.php';
session_start();

if (!isset($_SESSION['driver_id'])) {
    die(json_encode(["success" => false, "message" => "Unauthorized"]));
}

$data = json_decode(file_get_contents('php://input'), true);
$driverId = $_SESSION['driver_id'];
$bookingId = $data['booking_id'];

if (!$bookingId) {
    die(json_encode(["success" => false, "message" => "Booking ID is required"]));
}

$bookingRes = $conn->query("SELECT cab_id FROM bookings WHERE id = '$bookingId' AND driver_id = '$driverId' LIMIT 1");
$booking = $bookingRes ? $bookingRes->fetch_assoc() : null;

if (!$booking) {
    die(json_encode(["success" => false, "message" => "Booking not found for this driver"]));
}

// Update booking status
$sql = "UPDATE bookings SET status = 'completed' WHERE id = '$bookingId' AND driver_id = '$driverId'";

if ($conn->query($sql)) {
    // Update driver status back to available
    $conn->query("UPDATE drivers SET status = 'available' WHERE id = '$driverId'");
    if (!empty($booking['cab_id'])) {
        $conn->query("UPDATE cabs SET status = 'available' WHERE id = '" . (int) $booking['cab_id'] . "'");
    }
    echo json_encode(["success" => true, "message" => "Ride Completed"]);
} else {
    echo json_encode(["success" => false, "message" => "Error completion: " . $conn->error]);
}

$conn->close();
?>
