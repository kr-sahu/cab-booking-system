<?php
include 'inc/header.php';
include 'inc/sidebar.php';

$flashMessage = $_SESSION['client_flash'] ?? '';
unset($_SESSION['client_flash']);

$columnCheck = $conn->query("SHOW COLUMNS FROM users LIKE 'is_active'");
if ($columnCheck && $columnCheck->num_rows === 0) {
    $conn->query("ALTER TABLE users ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_user_status'])) {
    $userId = (int) ($_POST['user_id'] ?? 0);

    if ($userId > 0) {
        $statusResult = $conn->query("SELECT fullname, name, is_active FROM users WHERE id = {$userId} LIMIT 1");

        if ($statusResult && $statusResult->num_rows > 0) {
            $user = $statusResult->fetch_assoc();
            $nextStatus = (int) !((int) $user['is_active']);
            $displayName = $user['fullname'] ?: ($user['name'] ?: 'Customer');

            if ($conn->query("UPDATE users SET is_active = {$nextStatus} WHERE id = {$userId}")) {
                $_SESSION['client_flash'] = $nextStatus
                    ? htmlspecialchars($displayName) . ' has been unblocked.'
                    : htmlspecialchars($displayName) . ' has been blocked.';
            } else {
                $_SESSION['client_flash'] = 'Unable to update customer status.';
            }
        } else {
            $_SESSION['client_flash'] = 'Customer not found.';
        }
    } else {
        $_SESSION['client_flash'] = 'Invalid customer selected.';
    }

    header('Location: clients.php');
    exit();
}

// Fetch users from the main project database
$activeUsers = $conn->query("SELECT * FROM users WHERE COALESCE(is_active, 1) = 1 ORDER BY created_at DESC");
$blockedUsers = $conn->query("SELECT * FROM users WHERE COALESCE(is_active, 1) = 0 ORDER BY created_at DESC");
?>

<main class="main-content">
    <div class="top-bar">
        <h1>Registered Customers</h1>
        <div class="user-info">
            <span>Welcome, <strong><?php echo $_SESSION['admin_username']; ?></strong></span>
            <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Admin" style="width: 32px; height: 32px; border-radius: 50%;">
        </div>
    </div>

    <?php if ($flashMessage): ?>
        <div class="table-container" style="margin-bottom: 1.25rem; padding: 1rem 1.25rem; color: #166534; background: #dcfce7; border: 1px solid #bbf7d0;">
            <strong><?php echo $flashMessage; ?></strong>
        </div>
    <?php endif; ?>

    <div class="table-container">
        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem;">Active Customers</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Profile</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Joined Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($activeUsers && $activeUsers->num_rows > 0): ?>
                    <?php while($row = $activeUsers->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <img src="../<?php echo !empty($row['profile_image']) ? $row['profile_image'] : 'assets/img/default-avatar.png'; ?>" 
                                     onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($row['fullname']); ?>&background=random'"
                                     style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                            </td>
                            <td><strong><?php echo htmlspecialchars($row['fullname'] ?? $row['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('<?php echo ((int) ($row['is_active'] ?? 1) === 1) ? 'Block this customer?' : 'Unblock this customer?'; ?>');">
                                    <input type="hidden" name="user_id" value="<?php echo (int) $row['id']; ?>">
                                    <button type="submit" name="toggle_user_status" class="btn btn-sm" style="background: #fee2e2; color: #ef4444;" title="Block User">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">No active customers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-container" style="margin-top: 1.5rem;">
        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem;">Blocked Accounts</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Profile</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Blocked Account</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($blockedUsers && $blockedUsers->num_rows > 0): ?>
                    <?php while($row = $blockedUsers->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <img src="../<?php echo !empty($row['profile_image']) ? $row['profile_image'] : 'assets/img/default-avatar.png'; ?>" 
                                     onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($row['fullname'] ?? $row['name']); ?>&background=random'"
                                     style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                            </td>
                            <td><strong><?php echo htmlspecialchars($row['fullname'] ?? $row['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Unblock this customer?');">
                                    <input type="hidden" name="user_id" value="<?php echo (int) $row['id']; ?>">
                                    <button type="submit" name="toggle_user_status" class="btn btn-sm" style="background: #dcfce7; color: #16a34a;" title="Unblock User">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">No blocked accounts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
