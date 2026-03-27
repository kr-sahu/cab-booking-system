<?php
include 'inc/header.php';
include 'inc/sidebar.php';

$message = "";

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM cabs WHERE id = $id");
    header("Location: cabs.php");
    exit;
}

// Handle Add/Update Cab
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_cab'])) {
    $reg_no = $_POST['reg_no'];
    $cab_type = $_POST['cab_type'];
    $seats = $_POST['seats'];
    $model = $_POST['model'];
    $status = $_POST['status'];
    $driver_id = !empty($_POST['driver_id']) ? $_POST['driver_id'] : 'NULL';

    $conn->begin_transaction();

    try {
        if ($driver_id !== 'NULL') {
            $conn->query("UPDATE cabs SET driver_id = NULL WHERE driver_id = $driver_id AND reg_no != '" . $conn->real_escape_string($reg_no) . "'");
        }

        $sql = "INSERT INTO cabs (reg_no, cab_type, seats, model, driver_id, status) 
                VALUES ('$reg_no', '$cab_type', $seats, '$model', $driver_id, '$status')
                ON DUPLICATE KEY UPDATE 
                cab_type='$cab_type', seats=$seats, model='$model', driver_id=$driver_id, status='$status'";
        
        if (!$conn->query($sql)) {
            throw new Exception($conn->error);
        }

        $conn->commit();
        $message = "<div class='badge badge-success' style='display:block; margin-bottom:1rem; padding:1rem;'>Cab saved successfully!</div>";
    } catch (Exception $e) {
        $conn->rollback();
        $message = "<div class='badge badge-danger' style='display:block; margin-bottom:1rem; padding:1rem;'>Error: " . $e->getMessage() . "</div>";
    }
}

$drivers = $conn->query("SELECT id, name FROM drivers WHERE is_approved = 1");
$cabs = $conn->query("SELECT c.*, d.name as driver_name 
                      FROM cabs c 
                      LEFT JOIN drivers d ON c.driver_id = d.id 
                      ORDER BY c.created_at DESC");

$editCab = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $editRes = $conn->query("SELECT * FROM cabs WHERE id = $editId LIMIT 1");
    if ($editRes && $editRes->num_rows > 0) {
        $editCab = $editRes->fetch_assoc();
    }
}
?>

<main class="main-content">
    <div class="top-bar">
        <h1>Cab Management</h1>
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <button class="btn btn-primary" onclick="openCabForm()">
                <i class="fas fa-plus"></i> Add New Cab
            </button>
            <div class="user-info">
                <span>Welcome, <strong><?php echo $_SESSION['admin_username']; ?></strong></span>
                <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Admin" style="width: 32px; height: 32px; border-radius: 50%;">
            </div>
        </div>
    </div>

    <!-- Simple Add Form (Hidden by default) -->
    <div id="cabForm" class="table-container" style="display:<?php echo $editCab ? 'block' : 'none'; ?>; margin-bottom: 2rem; border: 1px solid #e2e8f0;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2><?php echo $editCab ? 'Edit Vehicle' : 'Add / Edit Vehicle'; ?></h2>
            <button type="button" class="btn btn-sm" onclick="closeCabForm()">Close</button>
        </div>
        <form method="POST" style="margin-top: 1rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <label>Registration Number</label>
                <input type="text" name="reg_no" required value="<?php echo htmlspecialchars($editCab['reg_no'] ?? ''); ?>" class="form-control" style="width:100%; padding:0.5rem; border-radius:0.5rem; margin-top:0.3rem;">
            </div>
            <div>
                <label>Cab Type</label>
                <select name="cab_type" class="form-control" style="width:100%; padding:0.5rem; border-radius:0.5rem; margin-top:0.3rem;">
                    <option value="Mini" <?php echo (($editCab['cab_type'] ?? '') === 'Mini') ? 'selected' : ''; ?>>Mini (Hatchback)</option>
                    <option value="Sedan" <?php echo (($editCab['cab_type'] ?? '') === 'Sedan') ? 'selected' : ''; ?>>Sedan</option>
                    <option value="SUV" <?php echo (($editCab['cab_type'] ?? '') === 'SUV') ? 'selected' : ''; ?>>SUV</option>
                    <option value="Luxury" <?php echo (($editCab['cab_type'] ?? '') === 'Luxury') ? 'selected' : ''; ?>>Luxury</option>
                </select>
            </div>
            <div>
                <label>Number of Seats</label>
                <input type="number" name="seats" required value="<?php echo htmlspecialchars($editCab['seats'] ?? '4'); ?>" class="form-control" style="width:100%; padding:0.5rem; border-radius:0.5rem; margin-top:0.3rem;">
            </div>
            <div>
                <label>Vehicle Model</label>
                <input type="text" name="model" required value="<?php echo htmlspecialchars($editCab['model'] ?? ''); ?>" class="form-control" style="width:100%; padding:0.5rem; border-radius:0.5rem; margin-top:0.3rem;" placeholder="e.g. Toyota Camry">
            </div>
            <div>
                <label>Assign Driver</label>
                <select name="driver_id" class="form-control" style="width:100%; padding:0.5rem; border-radius:0.5rem; margin-top:0.3rem;">
                    <option value="">-- None --</option>
                    <?php while($d = $drivers->fetch_assoc()): ?>
                        <option value="<?php echo $d['id']; ?>" <?php echo ((int) ($editCab['driver_id'] ?? 0) === (int) $d['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($d['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label>Status</label>
                <select name="status" class="form-control" style="width:100%; padding:0.5rem; border-radius:0.5rem; margin-top:0.3rem;">
                    <option value="available" <?php echo (($editCab['status'] ?? 'available') === 'available') ? 'selected' : ''; ?>>Available</option>
                    <option value="busy" <?php echo (($editCab['status'] ?? '') === 'busy') ? 'selected' : ''; ?>>Busy</option>
                    <option value="maintenance" <?php echo (($editCab['status'] ?? '') === 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                </select>
            </div>
            <div style="grid-column: span 2;">
                <button type="submit" name="save_cab" class="btn btn-primary"><?php echo $editCab ? 'Update Vehicle Details' : 'Save Vehicle Details'; ?></button>
            </div>
        </form>
    </div>

    <div class="table-container">
        <?php echo $message; ?>
        <h2>Vehicle Fleet</h2>
        <table>
            <thead>
                <tr>
                    <th>Reg No</th>
                    <th>Type</th>
                    <th>Seats</th>
                    <th>Model</th>
                    <th>Driver</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($cabs && $cabs->num_rows > 0): ?>
                    <?php while($row = $cabs->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['reg_no']); ?></strong></td>
                            <td><span class="badge badge-primary"><?php echo htmlspecialchars($row['cab_type']); ?></span></td>
                            <td><?php echo htmlspecialchars($row['seats']); ?> Seats</td>
                            <td><?php echo htmlspecialchars($row['model']); ?></td>
                            <td><?php echo htmlspecialchars($row['driver_name'] ?? 'Unassigned'); ?></td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $row['status'] == 'available' ? 'success' : ($row['status'] == 'busy' ? 'warning' : 'danger'); 
                                ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm" style="background: #e2e8f0;"><i class="fas fa-edit"></i></a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm" style="background: #fee2e2; color: #ef4444;" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align:center; padding: 2rem; color: #6b7280;">No cabs found in the database.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function openCabForm() {
            document.getElementById('cabForm').style.display = 'block';
        }

        function closeCabForm() {
            window.location.href = 'cabs.php';
        }
    </script>
</main>

</body>
</html>
