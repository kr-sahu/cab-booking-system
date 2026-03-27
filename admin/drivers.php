<?php
include 'inc/header.php';
include 'inc/sidebar.php';

// Handle Actions (Activate/Deactivate)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if ($action == 'activate') {
        $conn->query("UPDATE drivers SET is_active = 1 WHERE id = $id");
    } elseif ($action == 'deactivate') {
        $conn->query("UPDATE drivers SET is_active = 0 WHERE id = $id");
    } elseif ($action == 'delete') {
        // First, check for active bookings before deleting
        $check_bookings = $conn->query("SELECT id FROM bookings WHERE (driver_id = $id OR driver_name = (SELECT name FROM drivers WHERE id = $id)) AND status != 'completed' AND status != 'cancelled'");
        if($check_bookings->num_rows > 0) {
            header("Location: drivers.php?msg=" . urlencode("Cannot delete driver with active bookings!"));
            exit;
        }
        $conn->query("DELETE FROM drivers WHERE id = $id");
    }
    header("Location: drivers.php");
    exit;
}

// Fetch approved drivers with dynamic trip status based on bookings
$drivers = $conn->query("
    SELECT d.*, 
    (SELECT COUNT(*) FROM bookings b WHERE b.driver_id = d.id AND b.status IN ('confirmed')) as active_trips
    FROM drivers d 
    WHERE d.is_approved = 1 
    ORDER BY d.name ASC
");
?>

<main class="main-content">
    <div class="top-bar">
        <h1>Driver Management</h1>
        <div class="user-info">
            <span>Welcome, <strong><?php echo $_SESSION['admin_username']; ?></strong></span>
            <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Admin" style="width: 32px; height: 32px; border-radius: 50%;">
        </div>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-danger" style="background:#fee2e2; color:#ef4444; padding:1rem; border-radius:0.5rem; margin-bottom:1.5rem;">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <div class="table-container">
        <h2>Active Driver Partners</h2>
        <p style="font-size:0.875rem; color:var(--text-muted); margin-bottom:1.5rem;">List of all verified and approved drivers currently in the system.</p>
        <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>License</th>
                            <th>Trip Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($drivers && $drivers->num_rows > 0): ?>
                            <?php while($row = $drivers->fetch_assoc()): ?>
                                <tr>
                                    <td style="display:flex; align-items:center; gap:0.75rem;">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($row['name']) ?>&background=random&color=fff&bold=true" 
                                             data-original="../<?= $row['profile_image'] ?>"
                                             style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover;"
                                             onerror="if (this.src != 'https://ui-avatars.com/api/?name=<?= urlencode($row['name']) ?>&background=random&color=fff&bold=true') this.src = 'https://ui-avatars.com/api/?name=<?= urlencode($row['name']) ?>&background=random&color=fff&bold=true';">
                                        
                                        <script>
                                            // Handle the profile image path logic
                                            (function() {
                                                const img = document.currentScript.previousElementSibling;
                                                const original = img.getAttribute('data-original');
                                                if (original && original.trim() !== '../') {
                                                    img.src = original;
                                                }
                                            })();
                                        </script>
                                        <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                    <td><?php echo htmlspecialchars($row['license_no']); ?></td>
                                    <td>
                                        <?php if($row['active_trips'] > 0): ?>
                                            <span class="badge badge-warning">Busy</span>
                                        <?php else: ?>
                                            <span class="badge badge-<?php echo $row['status'] == 'available' ? 'success' : ($row['status'] == 'on_trip' ? 'warning' : 'danger'); ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $row['status'])); ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="display:flex; gap:0.5rem; align-items:center;">
                                        <?php if($row['is_active']): ?>
                                            <a href="?action=deactivate&id=<?php echo $row['id']; ?>" class="btn btn-sm" style="background:#fee2e2; color:#ef4444;" onclick="return confirm('Deactivate this account?')">Deactivate</a>
                                        <?php else: ?>
                                            <a href="?action=activate&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Activate Account</a>
                                        <?php endif; ?>
                                        
                                        <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm" style="background: #f3f4f6; color: #ef4444; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px;" onclick="return confirm('Are you sure you want to PERMANENTLY delete this driver?')" title="Delete Driver">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center; padding: 2rem; color: #6b7280;">No approved driver partners found.</td></tr>
                        <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
