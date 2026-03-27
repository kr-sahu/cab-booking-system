<?php
header('Content-Type: application/json');
require 'db_connect.php';

// Validation: Ensure a specific booking ID is provided for the status check
if (!isset($_GET['id'])) {
    die(json_encode(["success" => false, "message" => "Missing Booking ID"]));
}

$bookingId = intval($_GET['id']);

// Retrieve the current booking record from the database
$sql = "SELECT * FROM bookings WHERE id = $bookingId";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $booking = $result->fetch_assoc();
    
    // DEMO SIMULATION: If the ride is pending, simulate a driver confirmation event
    if ($booking['status'] === 'pending') {
        // Pool of iconic simulated drivers for the demonstration experience
        $drivers = [
            ['name' => 'Michael Knight', 'car' => 'Pontiac Firebird', 'plate' => 'KITT 2000'],
            ['name' => 'Daniel Morales', 'car' => 'Peugeot 406', 'plate' => 'TAXI-1'],
            ['name' => 'Ryan Gosling', 'car' => 'Chevy Malibu', 'plate' => 'DRIVE-01']
        ];
        $driver = $drivers[array_rand($drivers)];
        
        $dName = $driver['name'];
        $dCar = $driver['car'];
        $dPlate = $driver['plate'];
        
        // Update the booking status in the DB to reflect that a driver is confirmed and on trip
        $updateSql = "UPDATE bookings SET status = 'confirmed' WHERE id = $bookingId";
        $conn->query($updateSql);
        
        // Return simulated real-time driver data including mock GPS coordinates
        echo json_encode([
            "success" => true,
            "data" => [
                "status" => "confirmed",
                "name" => $dName,
                "car_model" => $dCar,
                "license_plate" => $dPlate,
                "rating" => "4.9",
                "current_lat" => 20.5937, // Simulated central India lat
                "current_lng" => 78.9629  // Simulated central India lng
            ]
        ]);
    } else {
        // Return existing driver details if the ride was already accepted or completed
        echo json_encode([
            "success" => true,
            "data" => [
                "status" => $booking['status'],
                "name" => "Michael Knight", 
                "car_model" => "Pontiac Firebird",
                "license_plate" => "KITT 2000",
                "rating" => "5.0"
            ]
        ]);
    }

} else {
    echo json_encode(["success" => false, "message" => "Booking not found"]);
}

$conn->close();
?>
