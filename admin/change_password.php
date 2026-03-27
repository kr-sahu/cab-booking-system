<?php
include 'inc/header.php';
include 'inc/sidebar.php';

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_pass = $_POST['current_pass'];
    $new_pass = $_POST['new_pass'];
    $id = $_SESSION['admin_id'];

    $stmt = $conn->prepare("SELECT password FROM admins WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (password_verify($current_pass, $user['password'])) {
        $hashed_new = password_hash($new_pass, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
        $update->bind_param("si", $hashed_new, $id);
        $update->execute();
        $message = "<div class='alert alert-success' style='background:#dcfce7; color:#166534; padding:1rem; border-radius:0.5rem;'>Password updated successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger' style='background:#fee2e2; color:#991b1b; padding:1rem; border-radius:0.5rem;'>Current password is incorrect.</div>";
    }
}
?>

<main class="main-content">
    <div class="top-bar">
        <h1>Account Settings</h1>
        <div class="user-info">
            <span>Welcome, <strong><?php echo $_SESSION['admin_username']; ?></strong></span>
            <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Admin" style="width: 32px; height: 32px; border-radius: 50%;">
        </div>
    </div>

    <div class="table-container" style="max-width: 500px;">
        <h2>Change Admin Password</h2>
        <div style="margin-top: 1rem;">
            <?php echo $message; ?>
        </div>
        <form method="POST" style="margin-top: 1.5rem;">
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Current Password</label>
                <input type="password" name="current_pass" required style="width:100%; padding:0.75rem; border-radius:0.5rem; border:1px solid #d1d5db; margin-top:0.5rem;">
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label>New Password</label>
                <input type="password" name="new_pass" required style="width:100%; padding:0.75rem; border-radius:0.5rem; border:1px solid #d1d5db; margin-top:0.5rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Update Password</button>
        </form>
    </div>
</main>
</body>
</html>
