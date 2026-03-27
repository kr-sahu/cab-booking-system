<?php
// Database configuration for local XAMPP environment
$host = "localhost";
$user = "root";
$pass = ""; // Default XAMPP password is empty
$db   = "cab_booking"; // Database name
$port = 3306;          // Default MySQL port

// Establish a connection to the MySQL database
$conn = new mysqli($host, $user, $pass, $db, $port);

// Terminate execution and return JSON error if connection fails
if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Database Connection Failed"
    ]));
}
?>
