<?php
require 'api/db_connect.php';
$res = $conn->query("DESCRIBE bookings");
while($row = $res->fetch_assoc()) print_r($row);
?>
