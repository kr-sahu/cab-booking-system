<?php
// Script to list all tables in 'cab_booking' database for diagnosis
require 'api/db_connect.php';
$res = $conn->query("SHOW TABLES");
while($row = $res->fetch_row()) {
    $t = $row[0];
    $c = $conn->query("SELECT count(*) as cnt FROM $t")->fetch_assoc();
    echo "Table: $t ($c[cnt] rows)\n";
}
$conn->close();
?>
