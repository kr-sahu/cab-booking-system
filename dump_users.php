<?php
// Dump contents of the users table for diagnosis
require 'api/db_connect.php';
$res = $conn->query("SELECT * FROM users");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
$conn->close();
?>
