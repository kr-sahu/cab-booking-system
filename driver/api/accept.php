<?php
header('Content-Type: application/json');
require '../../api/db_connect.php';
session_start();

if (!isset($_SESSION['driver_id'])) {
    die(json_encode(["success" => false, "message" => "Unauthorized"]));
}

$data = json_decode(file_get_contents('php://input'), true);
$driverId = $_SESSION['driver_id'];
$bookingId = isset($data['booking_id']) ? (int) $data['booking_id'] : 0;

if (!$bookingId) {
    die(json_encode(["success" => false, "message" => "Booking ID is required"]));
}

// 1. Check if driver is already on a trip
$checkTrip = $conn->query("SELECT id FROM bookings WHERE driver_id = '$driverId' AND status IN ('accepted', 'confirmed')");
if ($checkTrip->num_rows > 0) {
    die(json_encode(["success" => false, "message" => "You already have an active trip."]));
}

// 2. Fetch driver and assigned cab details
$driverRes = $conn->query("SELECT * FROM drivers WHERE id = '$driverId'");
$driver = $driverRes->fetch_assoc();

if (!$driver || (int) ($driver['is_approved'] ?? 0) !== 1 || (int) ($driver['is_active'] ?? 0) !== 1) {
    die(json_encode(["success" => false, "message" => "Your driver account is not active for trip acceptance."]));
}

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

$name = $conn->real_escape_string($driver['name']);
$contact = $conn->real_escape_string($driver['contact']);

try {
    $conn->begin_transaction();

    $checkBooking = $conn->query("SELECT status FROM bookings WHERE id = '$bookingId' FOR UPDATE");
    $bRow = $checkBooking ? $checkBooking->fetch_assoc() : null;

    if (!$bRow) {
        throw new Exception("Booking not found.");
    }

    if ($bRow['status'] !== 'pending') {
        throw new Exception("This ride is no longer available.");
    }

    $sql = "UPDATE bookings SET 
            driver_id = '$driverId', 
            cab_id = '$cabId',
            driver_name = '$name', 
            driver_contact = '$contact',
            cab_model = '$cab_model',
            cab_number = '$cab_number',
            status = 'confirmed' 
            WHERE id = '$bookingId'";

    if (!$conn->query($sql)) {
        throw new Exception("Error accepting ride: " . $conn->error);
    }

    if (!$conn->query("UPDATE drivers SET status = 'on_trip' WHERE id = '$driverId'")) {
        throw new Exception("Failed to update driver status.");
    }

    if (!$conn->query("UPDATE cabs SET status = 'busy' WHERE id = '$cabId'")) {
        throw new Exception("Failed to update cab status.");
    }

    $conn->commit();
    echo json_encode(["success" => true, "message" => "Ride Accepted"]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conn->close();
?>
