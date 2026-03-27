<?php
// Dump clients in cab_admin_db
$c = new mysqli('localhost', 'root', '', 'cab_admin_db');
$res = $c->query("SELECT * FROM clients");
while($row = $res->fetch_assoc()) print_r($row);
?>
