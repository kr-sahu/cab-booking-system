<?php
// Debugging script to count total bookings
require 'api/db_connect.php';
$res = $conn->query("SELECT count(*) as cnt FROM bookings");
$row = $res->fetch_assoc();
echo "Total Bookings in Database: " . $row['cnt'];
$conn->close();
?>
