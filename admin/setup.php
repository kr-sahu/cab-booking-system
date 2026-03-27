<?php
// setup.php - Updated Database Setup for Simplified Cab Booking System
require_once 'config/db.php';

echo "<h2>Starting System Setup...</h2>";

// Drop category-related constraints/tables if they exist
$conn->query("ALTER TABLE `cabs` DROP FOREIGN KEY IF EXISTS `cabs_ibfk_1` "); 

$tables = [
    // 1. Admins Table
    "CREATE TABLE IF NOT EXISTS `admins` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(50) NOT NULL UNIQUE,
        `password` varchar(255) NOT NULL,
        `fullname` varchar(100) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // 2. Drivers Table
    "CREATE TABLE IF NOT EXISTS `drivers` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `email` varchar(255) DEFAULT NULL,
        `password` varchar(255) DEFAULT NULL,
        `contact` varchar(20) NOT NULL,
        `license_no` varchar(50) NOT NULL UNIQUE,
        `gov_id` varchar(50) DEFAULT NULL,
        `address` text DEFAULT NULL,
        `gender` varchar(20) DEFAULT NULL,
        `profile_image` varchar(255) DEFAULT NULL,
        `status` enum('available', 'on_trip', 'offline') DEFAULT 'available',
        `is_approved` tinyint(1) DEFAULT 0, -- 0: Pending, 1: Approved, 2: Rejected
        `is_active` tinyint(1) DEFAULT 1,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // 3. Cabs Table - Updated with type and seats
    "CREATE TABLE IF NOT EXISTS `cabs` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `reg_no` varchar(20) NOT NULL UNIQUE,
        `cab_type` varchar(50) NOT NULL,
        `seats` int(11) NOT NULL DEFAULT 4,
        `model` varchar(100) NOT NULL,
        `driver_id` int(11) DEFAULT NULL,
        `status` enum('available', 'busy', 'maintenance') DEFAULT 'available',
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // Update existing cabs table if it already exists
    "ALTER TABLE `cabs` ADD COLUMN IF NOT EXISTS `cab_type` varchar(50) NOT NULL AFTER `reg_no`;",
    "ALTER TABLE `cabs` ADD COLUMN IF NOT EXISTS `seats` int(11) NOT NULL DEFAULT 4 AFTER `cab_type`;",
    "ALTER TABLE `drivers` ADD COLUMN IF NOT EXISTS `email` varchar(255) DEFAULT NULL AFTER `name`;",
    "ALTER TABLE `drivers` ADD COLUMN IF NOT EXISTS `password` varchar(255) DEFAULT NULL AFTER `email`;",
    "ALTER TABLE `drivers` ADD COLUMN IF NOT EXISTS `gov_id` varchar(50) DEFAULT NULL AFTER `license_no`;",
    "ALTER TABLE `drivers` ADD COLUMN IF NOT EXISTS `address` text DEFAULT NULL AFTER `gov_id`;",
    "ALTER TABLE `drivers` ADD COLUMN IF NOT EXISTS `gender` varchar(20) DEFAULT NULL AFTER `address`;",
    "ALTER TABLE `drivers` ADD COLUMN IF NOT EXISTS `profile_image` varchar(255) DEFAULT NULL AFTER `gender`;",

    // 4. Bookings Table Extensions
    "ALTER TABLE `bookings` ADD COLUMN IF NOT EXISTS `driver_id` int(11) DEFAULT NULL after `status`;",
    "ALTER TABLE `bookings` ADD COLUMN IF NOT EXISTS `cab_id` int(11) DEFAULT NULL after `driver_id`;",
    "ALTER TABLE `bookings` MODIFY COLUMN `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending';"
];

foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        // Success
    } else {
        echo "<p style='color:red;'>Error executing SQL: " . $conn->error . "</p>";
    }
}

// Insert Default Admin
$check_admin = $conn->query("SELECT id FROM admins WHERE username = 'admin'");
if ($check_admin->num_rows == 0) {
    $hashed_pass = password_hash('Password@123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO admins (username, password, fullname) VALUES ('admin', '$hashed_pass', 'System Administrator')");
    echo "<p style='color:green;'>✓ Default Admin Created (admin / Password@123)</p>";
}

// SEEDING DATA: Insert Sample Drivers (The "Random/Static" ones from the code)
$sample_drivers = [
    ['Arjun Singh', '+91 98765-43210', 'DL-1234567890'],
    ['Vikram Rathore', '+91 87654-32109', 'DL-0987654321'],
    ['Rahul Sharma', '+91 76543-21098', 'DL-5544332211'],
    ['Suresh Kumar', '+91 99887-76655', 'DL-6677889900'],
    ['Amit Patel', '+91 88776-65544', 'DL-1122334455']
];

foreach ($sample_drivers as $d) {
    $check = $conn->query("SELECT id FROM drivers WHERE license_no = '{$d[2]}'");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO drivers (name, contact, license_no, is_approved, status) VALUES ('{$d[0]}', '{$d[1]}', '{$d[2]}', 1, 'available')");
    }
}

// SEEDING DATA: Insert Sample Cabs (The "Random/Static" ones from the code)
$sample_cabs = [
    ['DL 01 AB 4821', 'Sedan', 4, 'Maruti Suzuki Dzire'],
    ['MH 12 QN 5604', 'Sedan', 4, 'Hyundai Aura'],
    ['KA 03 MP 7712', 'Sedan', 4, 'Toyota Etios'],
    ['WB 06 T 9145', 'Sedan', 4, 'Tata Tigor'],
    ['TS 09 LR 2286', 'Sedan', 4, 'Honda Amaze'],
    ['GJ 01 RX 6634', 'Mini', 4, 'Hyundai Xcent'],
    ['RJ 14 CP 3907', 'Mini', 4, 'Maruti WagonR'],
    ['UP 32 HT 5188', 'Sedan', 4, 'Honda City'],
    ['HR 26 DK 7420', 'Mini', 4, 'Renault Triber']
];

foreach ($sample_cabs as $c) {
    $check = $conn->query("SELECT id FROM cabs WHERE reg_no = '{$c[0]}'");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO cabs (reg_no, cab_type, seats, model, status) VALUES ('{$c[0]}', '{$c[1]}', {$c[2]}, '{$c[3]}', 'available')");
    }
}

$approvedDrivers = [];
$driverRows = $conn->query("SELECT id FROM drivers WHERE is_approved = 1 ORDER BY id ASC");
if ($driverRows) {
    while ($row = $driverRows->fetch_assoc()) {
        $approvedDrivers[] = (int) $row['id'];
    }
}

foreach ($sample_cabs as $index => $c) {
    if (isset($approvedDrivers[$index])) {
        $driverId = $approvedDrivers[$index];
        $conn->query("UPDATE cabs SET driver_id = $driverId WHERE reg_no = '{$c[0]}'");
    }
}

echo "<h3>Setup Complete! <a href='login.php'>Go to Login</a></h3>";
?>
