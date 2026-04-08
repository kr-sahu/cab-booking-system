<?php
header('Content-Type: application/json');
require 'db_connect.php';
session_start();

// Guard access for signed-in users only.
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["success" => false, "message" => "Unauthorized User"]));
}

// Read the booking payload from the request body.
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $userId = $_SESSION['user_id'];
    $pickup = $conn->real_escape_string($data['pickup']);
    $dest = $conn->real_escape_string($data['dest']);
    $distanceInput = trim((string) ($data['distance'] ?? ''));
    $date = $data['date'] ?? date('Y-m-d');
    $time = $data['time'] ?? date('H:i:s');
    $bookingDateTime = $conn->real_escape_string($date . ' ' . $time);
    
    if (empty($pickup) || empty($dest)) {
        die(json_encode(["success" => false, "message" => "Pickup and Destination locations are required."]));
    }

    if (!preg_match('/\d+(\.\d+)?/', $distanceInput, $distanceMatch)) {
        die(json_encode(["success" => false, "message" => "Invalid trip distance."]));
    }

    $distanceKm = (float) $distanceMatch[0];

    if ($distanceKm <= 0) {
        die(json_encode(["success" => false, "message" => "Trip distance must be greater than zero."]));
    }

    $fareValue = round($distanceKm * 10, 2);
    $fare = number_format($fareValue, 2, '.', '');
    $distance = $conn->real_escape_string(number_format($distanceKm, 1, '.', '') . ' km');
    
    $sql = "INSERT INTO bookings (user_id, pickup_location, destination, fare, distance, status, created_at) 
            VALUES ('$userId', '$pickup', '$dest', '$fare', '$distance', 'pending', '$bookingDateTime')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "booking_id" => $conn->insert_id, "message" => "Your booking is pending approval. You’ll be notified once it’s confirmed."]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid Data"]);
}

$conn->close();
?>
