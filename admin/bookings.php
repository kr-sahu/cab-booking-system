<?php
include 'inc/header.php';
include 'inc/sidebar.php';

// Filter Logic
$where = "WHERE 1=1";
if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status = $_GET['status'];
    $where .= " AND b.status = '$status'";
}

$bookings = $conn->query("SELECT b.*, u.fullname as customer 
                         FROM bookings b 
                         LEFT JOIN users u ON b.user_id = u.id 
                         $where
                         ORDER BY b.created_at DESC");
?>

<main class="main-content">
    <div class="top-bar">
        <h1>Booking Management</h1>
        <div class="user-info">
            <span>Welcome, <strong><?php echo $_SESSION['admin_username']; ?></strong></span>
            <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Admin" style="width: 32px; height: 32px; border-radius: 50%;">
        </div>
    </div>

    <div class="table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2>All Bookings</h2>
            <form method="GET" style="display: flex; gap: 0.5rem;">
                <select name="status" style="padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #d1d5db;">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Route</th>
                    <th>Fare</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($bookings && $bookings->num_rows > 0): ?>
                    <?php while($row = $bookings->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['customer'] ?? 'Guest'); ?></td>
                            <td><?php echo htmlspecialchars($row['pickup_location']); ?> → <?php echo htmlspecialchars($row['destination']); ?></td>
                            <td>₹<?php echo number_format($row['fare'], 2); ?></td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $row['status'] == 'completed' ? 'success' : ($row['status'] == 'pending' ? 'warning' : ($row['status'] == 'confirmed' ? 'primary' : 'danger')); 
                                ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="booking_details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Manage</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align:center; padding: 2rem; color: #6b7280;">No bookings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
