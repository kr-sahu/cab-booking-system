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

// 1. Check if driver is already on a trip
$checkTrip = $conn->query("SELECT id FROM bookings WHERE driver_id = '$driverId' AND status = 'accepted'");
if ($checkTrip->num_rows > 0) {
    die(json_encode(["success" => false, "message" => "You already have an active trip."]));
}

// 2. Fetch driver and assigned cab details
$driverRes = $conn->query("SELECT * FROM drivers WHERE id = '$driverId'");
$driver = $driverRes->fetch_assoc();

$cabRes = $conn->query("SELECT id, reg_no, model, status FROM cabs WHERE driver_id = '$driverId' LIMIT 1");
$cab = $cabRes->fetch_assoc();

if (!$cab) {
    die(json_encode(["success" => false, "message" => "No cab is assigned to your driver account. Please contact admin."]));
}

if ($cab['status'] !== 'available') {
    die(json_encode(["success" => false, "message" => "Your assigned cab is not available right now."]));
}

$cabId = (int) $cab['id'];
$cab_number = $conn->real_escape_string($cab['reg_no']);
$cab_model = $conn->real_escape_string($cab['model']);

// 3. Attempt to accept the booking (atomically if possible, but here simple UPDATE)
// Ensure booking is still pending
$checkBooking = $conn->query("SELECT status FROM bookings WHERE id = '$bookingId'");
$bRow = $checkBooking->fetch_assoc();

if($bRow['status'] !== 'pending') {
    die(json_encode(["success" => false, "message" => "This ride is no longer available."]));
}

$name = $conn->real_escape_string($driver['name']);
$contact = $conn->real_escape_string($driver['contact']);

// Update booking
$sql = "UPDATE bookings SET 
        driver_id = '$driverId', 
        cab_id = '$cabId',
        driver_name = '$name', 
        driver_contact = '$contact',
        cab_model = '$cab_model',
        cab_number = '$cab_number',
        status = 'accepted' 
        WHERE id = '$bookingId'";

if ($conn->query($sql)) {
    // Update driver status to on_trip
    $conn->query("UPDATE drivers SET status = 'on_trip' WHERE id = '$driverId'");
    $conn->query("UPDATE cabs SET status = 'busy' WHERE id = '$cabId'");
    echo json_encode(["success" => true, "message" => "Ride Accepted"]);
} else {
    echo json_encode(["success" => false, "message" => "Error accepting ride: " . $conn->error]);
}

$conn->close();
?>
