<?php
require 'db_connect.php';
// session_start for authentication tracking
session_start();
header('Content-Type: application/json');

// Authorization Guard: Only registered users can manage payment cards
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["success" => false, "message" => "Unauthorized"]));
}

$userId = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

// GET REQUEST: Retrieve all saved payment cards for the current user
if ($method === 'GET') {
    $result = $conn->query("SELECT * FROM user_cards WHERE user_id = $userId ORDER BY id DESC");
    $cards = [];
    while($row = $result->fetch_assoc()) {
        $cards[] = $row;
    }
    echo json_encode(["success" => true, "cards" => $cards]);
}

// POST REQUEST: Securely store a new payment card reference
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Preparation and sanitization of card metadata
    $holder = $conn->real_escape_string($data['holder']);
    $fullNum = $data['number'];
    $expiry = $conn->real_escape_string($data['expiry']);
    
    // Logic to identify card brand (Visa/Mastercard) and store only last 4 digits
    $last4 = substr($fullNum, -4);
    $brand = ($fullNum[0] == '4') ? 'Visa' : 'Mastercard';
    
    $sql = "INSERT INTO user_cards (user_id, card_holder, card_brand, last_four, expiry) 
            VALUES ('$userId', '$holder', '$brand', '$last4', '$expiry')";
            
    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => "Card Added"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error saving card"]);
    }
}
$conn->close();
?>