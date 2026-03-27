<?php
include 'inc/header.php';
include 'inc/sidebar.php';

if (!isset($_GET['id'])) {
    header("Location: bookings.php");
    exit;
}

$id = $_GET['id'];
$message = "";

// Fetch Current Booking Data
$booking = $conn->query("SELECT b.*, u.fullname as customer, u.email as customer_email 
                         FROM bookings b 
                         LEFT JOIN users u ON b.user_id = u.id 
                         WHERE b.id = $id")->fetch_assoc();

if (!$booking) {
    header("Location: bookings.php");
    exit;
}

// Handle Status Updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = $_POST['status'];
    $driver_id = !empty($_POST['driver_id']) ? $_POST['driver_id'] : null;
    $cab_id = !empty($_POST['cab_id']) ? $_POST['cab_id'] : null;
    
    $old_status = $booking['status'];
    $old_driver_id = $booking['driver_id'];
    $old_cab_id = $booking['cab_id'];

    // Start Transaction for data integrity
    $conn->begin_transaction();

    try {
        // 1. Logic for confirming a ride
        if ($new_status == 'confirmed' && $old_status == 'pending') {
            if (!$driver_id || !$cab_id) {
                throw new Exception("Please assign both a driver and a cab to confirm the ride.");
            }
            // Transition: Driver to On Trip, Cab to Busy
            $conn->query("UPDATE drivers SET status = 'on_trip' WHERE id = $driver_id");
            $conn->query("UPDATE cabs SET status = 'busy' WHERE id = $cab_id");
        }

        // 2. Logic for completing a ride
        if ($new_status == 'completed' && $old_status == 'confirmed') {
            // Transition: Release Driver and Cab
            $conn->query("UPDATE drivers SET status = 'available' WHERE id = $old_driver_id");
            $conn->query("UPDATE cabs SET status = 'available' WHERE id = $old_cab_id");
        }

        // 3. Logic for cancelling a confirmed ride
        if ($new_status == 'cancelled' && $old_status == 'confirmed') {
            // Transition: Release Driver and Cab
            $conn->query("UPDATE drivers SET status = 'available' WHERE id = $old_driver_id");
            $conn->query("UPDATE cabs SET status = 'available' WHERE id = $old_cab_id");
        }

        // Update the booking record
        $stmt = $conn->prepare("UPDATE bookings SET status = ?, driver_id = ?, cab_id = ? WHERE id = ?");
        $stmt->bind_param("siii", $new_status, $driver_id, $cab_id, $id);
        $stmt->execute();

        $conn->commit();
        echo "<script>alert('Booking status updated successfully!'); window.location.href='booking_details.php?id=$id';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        $message = "<div class='badge badge-danger' style='width:100%; margin-bottom:1rem; padding:1rem;'>Error: " . $e->getMessage() . "</div>";
    }
}

// Fetch lists for assignment (Only available ones)
// Note: We also include the currently assigned ones so they appear in the dropdown
$current_driver = $booking['driver_id'] ?? 0;
$current_cab = $booking['cab_id'] ?? 0;

$drivers = $conn->query("SELECT id, name FROM drivers WHERE (status = 'available' AND is_approved = 1) OR id = $current_driver");
$cabs = $conn->query("SELECT id, reg_no, model FROM cabs WHERE status = 'available' OR id = $current_cab");
?>

<main class="main-content">
    <div class="top-bar">
        <h1>Management: Ride #<?php echo $id; ?></h1>
        <a href="bookings.php" class="btn btn-sm" style="background:#e2e8f0;">Back to List</a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <div class="table-container">
            <?php echo $message; ?>
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <h2>Ride Details</h2>
                <span class="badge badge-<?php 
                    $stat = !empty($booking['status']) ? $booking['status'] : 'pending';
                    echo $stat == 'completed' ? 'success' : ($stat == 'pending' ? 'warning' : ($stat == 'confirmed' ? 'primary' : 'danger')); 
                ?>" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                    STATUS: <?php echo !empty($booking['status']) ? strtoupper($booking['status']) : 'UNKNOWN'; ?>
                </span>
            </div>
            
            <div style="margin-top: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <p style="color:var(--text-muted); font-size: 0.8rem; text-transform:uppercase; font-weight:bold;">Customer Info</p>
                    <p style="margin-top:0.5rem;"><strong>Name:</strong> <?php echo htmlspecialchars($booking['customer'] ?? 'Guest'); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['customer_email'] ?? '---'); ?></p>
                    
                    <p style="color:var(--text-muted); font-size: 0.8rem; text-transform:uppercase; font-weight:bold; margin-top:1.5rem;">Trip Stats</p>
                    <p style="margin-top:0.5rem;"><strong>Route:</strong> <?php echo htmlspecialchars($booking['pickup_location']); ?> → <?php echo htmlspecialchars($booking['destination']); ?></p>
                    <p><strong>Distance:</strong> <?php echo htmlspecialchars($booking['distance']); ?></p>
                    <p><strong>Fare:</strong> <span style="font-size:1.2rem; color:var(--primary); font-weight:bold;">₹<?php echo number_format($booking['fare'], 2); ?></span></p>
                </div>
                <div>
                    <p style="color:var(--text-muted); font-size: 0.8rem; text-transform:uppercase; font-weight:bold;">Current Assignment</p>
                    <?php if(!empty($booking['driver_id'])): ?>
                        <?php 
                        $d_info = $conn->query("SELECT name, contact FROM drivers WHERE id = ".$booking['driver_id'])->fetch_assoc();
                        $c_info = null;
                        if (!empty($booking['cab_id'])) {
                            $c_info = $conn->query("SELECT reg_no, model FROM cabs WHERE id = ".$booking['cab_id'])->fetch_assoc();
                        }
                        ?>
                        <div style="margin-top:0.5rem; background: #f8fafc; padding: 1rem; border-radius: 1rem; border: 1px solid #e2e8f0;">
                            <p><strong>Driver:</strong> <?php echo htmlspecialchars($d_info['name'] ?? 'Unknown'); ?> (<?php echo $d_info['contact'] ?? '---'; ?>)</p>
                            <p><strong>Vehicle:</strong> <?php echo $c_info ? htmlspecialchars($c_info['model'] . " (" . $c_info['reg_no'] . ")") : '<span style="color:orange;">Not Assigned</span>'; ?></p>
                        </div>
                    <?php else: ?>
                        <p style="margin-top:0.5rem; color: #ef4444; font-weight:bold;">Untracked / No Driver Assigned</p>
                    <?php endif; ?>
                    <p style="margin-top:1rem; font-size:0.8rem; color:var(--text-muted);">Booked at: <?php echo date('M d, Y H:i', strtotime($booking['created_at'])); ?></p>
                </div>
            </div>
        </div>

        <div class="table-container">
            <h2>Admin Control Panel</h2>
            <p style="font-size: 0.8rem; color:var(--text-muted); margin-top: 0.5rem;">Manage the lifecycle of this ride request.</p>
            
            <form method="POST" style="margin-top: 1.5rem;">
                <div class="form-group">
                    <label>Lifecycle Status</label>
                    <select name="status" class="form-control" style="width:100%; padding:0.75rem; border-radius:0.75rem; margin-top:0.5rem;">
                        <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Pending (Awaiting Action)</option>
                        <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed (On Trip)</option>
                        <option value="completed" <?php echo $booking['status'] == 'completed' ? 'selected' : ''; ?>>Completed (Mark as Done)</option>
                        <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled (Reject)</option>
                    </select>
                </div>

                <div class="form-group" style="margin-top:1.5rem;">
                    <label>Assign Driver</label>
                    <select name="driver_id" class="form-control" style="width:100%; padding:0.75rem; border-radius:0.75rem; margin-top:0.5rem;">
                        <option value="">-- Choose Available Driver --</option>
                        <?php while($d = $drivers->fetch_assoc()): ?>
                            <option value="<?php echo $d['id']; ?>" <?php echo $booking['driver_id'] == $d['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($d['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-top:1.5rem;">
                    <label>Assign Cab</label>
                    <select name="cab_id" class="form-control" style="width:100%; padding:0.75rem; border-radius:0.75rem; margin-top:0.5rem;">
                        <option value="">-- Choose Available Cab --</option>
                        <?php while($c = $cabs->fetch_assoc()): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo $booking['cab_id'] == $c['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['reg_no']); ?> (<?php echo htmlspecialchars($c['model']); ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:2rem; padding: 1rem; font-size: 1rem;">
                    Execute Lifecycle Update
                </button>
            </form>
            
            <?php if($booking['status'] == 'confirmed'): ?>
                <div style="margin-top:1.5rem; text-align:center; padding: 1rem; border: 1px dashed var(--primary); border-radius: 1rem;">
                    <i class="fas fa-info-circle" style="color:var(--primary);"></i>
                    <p style="font-size:0.75rem; color:var(--text-muted); margin-top:0.5rem;">This trip is currently <strong>Ongoing</strong>. Marking it as <strong>Completed</strong> will release the driver and cab back to the pool.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
</body>
</html>
