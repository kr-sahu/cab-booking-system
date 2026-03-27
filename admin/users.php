<?php
include 'inc/header.php';
include 'inc/sidebar.php';

$conn->query("
    CREATE TABLE IF NOT EXISTS admins (
        id INT(11) NOT NULL AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        fullname VARCHAR(100) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

$flashMessage = $_SESSION['admin_users_flash_message'] ?? '';
$flashType = $_SESSION['admin_users_flash_type'] ?? 'success';
unset($_SESSION['admin_users_flash_message'], $_SESSION['admin_users_flash_type']);

$activeModal = '';
$formErrors = [];
$editFormData = [
    'id' => 0,
    'fullname' => '',
    'username' => ''
];
$addFormData = [
    'fullname' => '',
    'username' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_admin') {
        $fullname = trim($_POST['fullname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $addFormData['fullname'] = $fullname;
        $addFormData['username'] = $username;

        if ($fullname === '' || $username === '' || $password === '' || $confirmPassword === '') {
            $formErrors[] = 'Fill in all fields to create a new admin.';
        } elseif ($password !== $confirmPassword) {
            $formErrors[] = 'Password and confirm password must match.';
        } elseif (strlen($password) < 8) {
            $formErrors[] = 'Admin password must be at least 8 characters.';
        } else {
            $check = $conn->prepare("SELECT id FROM admins WHERE username = ? LIMIT 1");
            $check->bind_param("s", $username);
            $check->execute();
            $exists = $check->get_result();

            if ($exists->num_rows > 0) {
                $formErrors[] = 'That username is already in use.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $insert = $conn->prepare("INSERT INTO admins (fullname, username, password) VALUES (?, ?, ?)");
                $insert->bind_param("sss", $fullname, $username, $hashedPassword);

                if ($insert->execute()) {
                    $_SESSION['admin_users_flash_message'] = 'New administrator added successfully.';
                    $_SESSION['admin_users_flash_type'] = 'success';
                    header('Location: users.php');
                    exit;
                }

                $formErrors[] = 'Unable to add the administrator right now.';
            }
        }

        $activeModal = 'add';
        $flashMessage = implode(' ', $formErrors);
        $flashType = 'danger';
    }

    if ($action === 'update_admin') {
        $adminId = (int) ($_POST['admin_id'] ?? 0);
        $fullname = trim($_POST['fullname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $editFormData['id'] = $adminId;
        $editFormData['fullname'] = $fullname;
        $editFormData['username'] = $username;

        if ($adminId <= 0 || $fullname === '' || $username === '') {
            $formErrors[] = 'Full name and username are required.';
        } else {
            $check = $conn->prepare("SELECT id FROM admins WHERE username = ? AND id != ? LIMIT 1");
            $check->bind_param("si", $username, $adminId);
            $check->execute();
            $exists = $check->get_result();

            if ($exists->num_rows > 0) {
                $formErrors[] = 'That username is already assigned to another admin.';
            }

            if ($newPassword !== '' || $confirmPassword !== '') {
                if ($newPassword !== $confirmPassword) {
                    $formErrors[] = 'New password and confirm password must match.';
                } elseif (strlen($newPassword) < 8) {
                    $formErrors[] = 'New password must be at least 8 characters.';
                }
            }
        }

        if (empty($formErrors)) {
            $update = $conn->prepare("UPDATE admins SET fullname = ?, username = ? WHERE id = ?");
            $update->bind_param("ssi", $fullname, $username, $adminId);
            $update->execute();

            if ($newPassword !== '') {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $passwordUpdate = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
                $passwordUpdate->bind_param("si", $hashedPassword, $adminId);
                $passwordUpdate->execute();
            }

            if ((int) $_SESSION['admin_id'] === $adminId) {
                $_SESSION['admin_username'] = $username;
            }

            $_SESSION['admin_users_flash_message'] = 'Administrator details updated successfully.';
            $_SESSION['admin_users_flash_type'] = 'success';
            header('Location: users.php');
            exit;
        }

        $activeModal = 'edit';
        $flashMessage = implode(' ', $formErrors);
        $flashType = 'danger';
    }

    if ($action === 'delete_admin') {
        $adminId = (int) ($_POST['admin_id'] ?? 0);

        if ($adminId === (int) $_SESSION['admin_id']) {
            $_SESSION['admin_users_flash_message'] = 'You cannot delete the admin account you are currently using.';
            $_SESSION['admin_users_flash_type'] = 'danger';
        } else {
            $delete = $conn->prepare("DELETE FROM admins WHERE id = ? AND username != 'admin'");
            $delete->bind_param("i", $adminId);
            $delete->execute();

            $_SESSION['admin_users_flash_message'] = $delete->affected_rows > 0
                ? 'Administrator removed successfully.'
                : 'Default admin cannot be deleted.';
            $_SESSION['admin_users_flash_type'] = $delete->affected_rows > 0 ? 'success' : 'danger';
        }

        header('Location: users.php');
        exit;
    }
}

$admins = $conn->query("SELECT * FROM admins ORDER BY created_at DESC, id DESC");

$editTargetId = 0;
if ($activeModal === 'edit') {
    $editTargetId = (int) $editFormData['id'];
} elseif (isset($_GET['edit'])) {
    $editTargetId = (int) $_GET['edit'];
    $activeModal = $editTargetId > 0 ? 'edit' : '';
}

if ($activeModal === '' && isset($_GET['add'])) {
    $activeModal = 'add';
}

if ($activeModal === 'edit' && $editTargetId > 0 && $editFormData['id'] === 0) {
    $editStmt = $conn->prepare("SELECT id, fullname, username FROM admins WHERE id = ? LIMIT 1");
    $editStmt->bind_param("i", $editTargetId);
    $editStmt->execute();
    $editResult = $editStmt->get_result();

    if ($editAdmin = $editResult->fetch_assoc()) {
        $editFormData['id'] = (int) $editAdmin['id'];
        $editFormData['fullname'] = $editAdmin['fullname'];
        $editFormData['username'] = $editAdmin['username'];
    } else {
        $activeModal = '';
    }
}
?>

<main class="main-content">
    <style>
        .page-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .flash-banner {
            margin-bottom: 1.25rem;
            padding: 1rem 1.1rem;
            border-radius: 1rem;
            font-size: 0.92rem;
            font-weight: 600;
        }

        .flash-banner.success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .flash-banner.danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .action-row {
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .icon-btn {
            width: 42px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.9rem;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .icon-btn.edit {
            background: #e2e8f0;
            color: #0f172a;
        }

        .icon-btn.edit:hover {
            background: #cbd5e1;
        }

        .icon-btn.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .icon-btn.delete:hover {
            background: #fecaca;
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1100;
            padding: 1.25rem;
        }

        .modal-card {
            width: min(100%, 560px);
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 30px 70px rgba(15, 23, 42, 0.22);
            overflow: hidden;
        }

        .modal-header {
            padding: 1.35rem 1.5rem 1rem;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-header h2 {
            font-size: 1.3rem;
            color: #0f172a;
            margin-bottom: 0.3rem;
        }

        .modal-header p {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .modal-close {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            border: none;
            background: #f1f5f9;
            color: #475569;
            cursor: pointer;
        }

        .modal-body {
            padding: 1.4rem 1.5rem 1.5rem;
        }

        .modal-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .modal-field {
            margin-bottom: 1rem;
        }

        .modal-field.full {
            grid-column: 1 / -1;
        }

        .modal-field label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .modal-field input {
            width: 100%;
            padding: 0.95rem 1rem;
            border: 1px solid #dbe5f0;
            border-radius: 1rem;
            background: #f8fafc;
            outline: none;
            transition: 0.2s ease;
        }

        .modal-field input:focus {
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.14);
        }

        .modal-note {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 0.9rem 1rem;
            border-radius: 1rem;
            font-size: 0.88rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #0f172a;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
        }

        @media (max-width: 720px) {
            .modal-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="top-bar">
        <h1>System Administrators</h1>
        <div class="page-actions">
            <a href="users.php?add=1" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add New Admin</a>
            <div class="user-info">
                <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></span>
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['admin_username']); ?>&background=6366f1&color=fff" alt="Admin" style="width: 32px; height: 32px; border-radius: 50%;">
            </div>
        </div>
    </div>

    <?php if ($flashMessage !== ''): ?>
        <div class="flash-banner <?php echo $flashType === 'danger' ? 'danger' : 'success'; ?>">
            <?php echo htmlspecialchars($flashMessage); ?>
        </div>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($admins && $admins->num_rows > 0): ?>
                    <?php while ($row = $admins->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo (int) $row['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['fullname']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <div class="action-row">
                                    <a href="users.php?edit=<?php echo (int) $row['id']; ?>" class="icon-btn edit" title="Edit admin">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <?php if ($row['username'] !== 'admin'): ?>
                                        <form method="POST" onsubmit="return confirm('Remove this administrator?');" style="display:inline;">
                                            <input type="hidden" name="action" value="delete_admin">
                                            <input type="hidden" name="admin_id" value="<?php echo (int) $row['id']; ?>">
                                            <button type="submit" class="icon-btn delete" title="Delete admin">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">No administrators found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($activeModal === 'add'): ?>
        <div class="modal-backdrop" onclick="if (event.target === this) window.location.href='users.php';">
            <div class="modal-card">
                <div class="modal-header">
                    <div>
                        <h2>Add New Administrator</h2>
                        <p>Create another admin account with its own username and password.</p>
                    </div>
                    <button type="button" class="modal-close" onclick="window.location.href='users.php'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="add_admin">
                        <div class="modal-grid">
                            <div class="modal-field full">
                                <label for="add_fullname">Full Name</label>
                                <input type="text" id="add_fullname" name="fullname" value="<?php echo htmlspecialchars($addFormData['fullname']); ?>" required>
                            </div>
                            <div class="modal-field">
                                <label for="add_username">Username</label>
                                <input type="text" id="add_username" name="username" value="<?php echo htmlspecialchars($addFormData['username']); ?>" required>
                            </div>
                            <div class="modal-field">
                                <label for="add_password">Password</label>
                                <input type="password" id="add_password" name="password" required>
                            </div>
                            <div class="modal-field full">
                                <label for="add_confirm_password">Confirm Password</label>
                                <input type="password" id="add_confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        <div class="modal-actions">
                            <a href="users.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($activeModal === 'edit' && $editFormData['id'] > 0): ?>
        <div class="modal-backdrop" onclick="if (event.target === this) window.location.href='users.php';">
            <div class="modal-card">
                <div class="modal-header">
                    <div>
                        <h2>Edit Administrator</h2>
                    </div>
                    <button type="button" class="modal-close" onclick="window.location.href='users.php'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_admin">
                        <input type="hidden" name="admin_id" value="<?php echo (int) $editFormData['id']; ?>">
                        <div class="modal-grid">
                            <div class="modal-field full">
                                <label for="edit_fullname">Full Name</label>
                                <input type="text" id="edit_fullname" name="fullname" value="<?php echo htmlspecialchars($editFormData['fullname']); ?>" required>
                            </div>
                            <div class="modal-field full">
                                <label for="edit_username">Username</label>
                                <input type="text" id="edit_username" name="username" value="<?php echo htmlspecialchars($editFormData['username']); ?>" required>
                            </div>
                            <div class="modal-field">
                                <label for="edit_password">New Password</label>
                                <input type="password" id="edit_password" name="new_password" placeholder="Leave blank to keep current password">
                            </div>
                            <div class="modal-field">
                                <label for="edit_confirm_password">Confirm Password</label>
                                <input type="password" id="edit_confirm_password" name="confirm_password" placeholder="Repeat the new password">
                            </div>
                        </div>
                        <div class="modal-actions">
                            <a href="users.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

</body>
</html>
