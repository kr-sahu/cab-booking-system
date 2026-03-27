<?php
header('Content-Type: application/json');
require '../api/db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM drivers WHERE id = $id");
    
    if ($result && $result->num_rows > 0) {
        $driver = $result->fetch_assoc();
        
        // Format date
        $driver['formatted_date'] = date('M d, Y', strtotime($driver['created_at']));
        
        // Handle status label
        $statusMap = [0 => 'Pending', 1 => 'Approved', 2 => 'Rejected'];
        $driver['status_label'] = $statusMap[$driver['is_approved']] ?? 'Unknown';
        
        echo json_encode(["success" => true, "data" => $driver]);
    } else {
        echo json_encode(["success" => false, "message" => "Driver not found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No ID provided."]);
}

$conn->close();
?>
