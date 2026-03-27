<?php
// Multi-Database check script (Fixed Syntax)
$users = [
    ['db' => 'cab_booking', 'col' => 'email', 'val' => 'animebs.net8421@gmail.com'],
    ['db' => 'cab_admin_db', 'col' => 'username', 'val' => 'ember']
];

foreach ($users as $u) {
    $dbName = $u['db'];
    $colName = $u['col'];
    $valName = $u['val'];
    
    $c = @new mysqli('localhost', 'root', '', $dbName);
    if ($c->connect_error) {
        echo "Could not connect to $dbName\n";
        continue;
    }
    
    $res = $c->query("SELECT id FROM users WHERE $colName = '$valName'");
    if($res && $row = $res->fetch_assoc()) {
        $uid = $row['id'];
        echo "Found User ID $uid in Database '$dbName'. Deleting related bookings...\n";
        $c->query("DELETE FROM bookings WHERE user_id = $uid");
        echo "SUCCESS: Deleted " . $c->affected_rows . " records from '$dbName.bookings'.\n";
    }
    $c->close();
}
?>
