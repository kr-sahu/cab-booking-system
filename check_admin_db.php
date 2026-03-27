<?php
// Check existence of cab_admin_db
$c = @new mysqli('localhost', 'root', '', 'cab_admin_db');
if ($c->connect_error) {
    echo "NO DATABASE: cab_admin_db";
} else {
    $res = $c->query("SHOW TABLES");
    while($row = $res->fetch_row()) echo $row[0] . "\n";
}
?>
