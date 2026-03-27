<?php
// Atomic purge script for 'ember'
$dbs = ['cab_booking', 'cab_admin_db'];
foreach ($dbs as $db) {
    $c = @new mysqli('localhost', 'root', '', $db);
    if ($c->connect_error) continue;
    
    // Purge bookings where user_id matches 'ember' (ID 4 as per dump)
    $c->query("DELETE FROM bookings WHERE user_id = 4");
    echo "Poured Database [$db]: " . $c->affected_rows . " rows deleted.\n";
    $c->close();
}
?>
