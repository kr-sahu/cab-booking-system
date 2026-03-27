<?php
include 'inc/header.php';
include 'inc/sidebar.php';

// Handle Actions (Approve/Reject)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if ($action == 'approve') {
        // Approve driver: approval_status = 1 (APPROVED), is_active = 1, status = available
        $conn->query("UPDATE drivers SET is_approved = 1, is_active = 1, status = 'available' WHERE id = $id");
        $msg = "Driver application approved successfully!";
    } elseif ($action == 'reject') {
        // Reject driver: approval_status = 2 (REJECTED), is_active = 0
        $conn->query("UPDATE drivers SET is_approved = 2, is_active = 0 WHERE id = $id");
        $msg = "Driver application rejected.";
    }
    header("Location: driver_applications.php?msg=" . urlencode($msg));
    exit;
}

// Fetch ONLY pending applications (is_approved = 0)
$applications = $conn->query("SELECT * FROM drivers WHERE is_approved = 0 ORDER BY created_at DESC");
?>

<main class="main-content">
    <div class="top-bar">
        <h1>Driver Applications</h1>
        <div class="user-info">
            <span>Welcome, <strong><?php echo $_SESSION['admin_username']; ?></strong></span>
            <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Admin" style="width: 32px; height: 32px; border-radius: 50%;">
        </div>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success" style="background:#dcfce7; color:#166534; padding:1rem; border-radius:0.5rem; margin-bottom:1.5rem;">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <div class="table-container">
        <h2>Pending Approvals</h2>
        <p style="font-size:0.875rem; color:var(--text-muted); margin-bottom:1.5rem;">The following individuals have applied to drive with Zuber.</p>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>License No</th>
                    <th>Applied On</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($applications && $applications->num_rows > 0): ?>
                    <?php while($row = $applications->fetch_assoc()): ?>
                        <tr>
                            <td style="display:flex; align-items:center; gap:0.75rem;">
                                <img src="../<?= !empty($row['profile_image']) ? $row['profile_image'] : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $row['id'] ?>" style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover;">
                                <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($row['contact']); ?></td>
                            <td><?php echo htmlspecialchars($row['license_no']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td><span class="badge badge-warning">Pending</span></td>
                            <td style="display:flex; gap: 0.5rem; align-items:center;">
                                <button onclick="viewDetails(<?php echo $row['id']; ?>)" class="btn btn-sm" style="background: #f3f4f6; color: #374151;" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" onclick="return confirm('Approve this driver?')">Approve</a>
                                <a href="?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-sm" style="background:#fee2e2; color:#ef4444;" onclick="return confirm('Reject this application?')">Reject</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center; padding: 2rem; color: #6b7280;">No pending driver applications found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:10000; display:none; align-items:center; justify-content:center; padding:1rem;">
        <div class="modal-content" style="background:white; width:100%; max-width:600px; border-radius:1.5rem; padding:2rem; position:relative; max-height:90vh; overflow-y:auto;">
            <button onclick="closeModal()" style="position:absolute; top:1.5rem; right:1.5rem; border:none; background:none; cursor:pointer; font-size:1.25rem; color:#9ca3af;"><i class="fas fa-times"></i></button>
            
            <h2 id="modalTitle" style="font-size:1.5rem; font-weight:800; margin-bottom:1.5rem; border-bottom:1px solid #f3f4f6; padding-bottom:1rem;">Application Details</h2>
            
            <div id="modalBody" class="grid grid-cols-2 gap-6" style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;">
                <!-- Content loaded via JS -->
                <div style="text-align:center; grid-column: span 2; padding: 2rem;">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function viewDetails(id) {
        const modal = document.getElementById('detailsModal');
        const body = document.getElementById('modalBody');
        modal.style.display = 'flex';
        body.innerHTML = '<div style="text-align:center; grid-column: span 2; padding: 2rem;"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';

        fetch(`get_driver_details.php?id=${id}`)
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    const d = res.data;
                    body.innerHTML = `
                        <div style="grid-column: span 2; display:flex; justify-content:center; margin-bottom:1.5rem;">
                            <div style="width:120px; height:120px; border-radius:1rem; overflow:hidden; border:4px solid #f3f4f6; background:#f9fafb;">
                                <img src="../${d.profile_image || 'assets/default-user.png'}" style="width:100%; height:100%; object-cover;" onerror="this.src='https://api.dicebear.com/7.x/avataaars/svg?seed=${d.id}'">
                            </div>
                        </div>
                        
                        <div>
                            <label style="display:block; font-size:10px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.25rem;">Full Name</label>
                            <p style="font-weight:700;">${d.name}</p>
                        </div>
                        <div>
                            <label style="display:block; font-size:10px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.25rem;">Contact Number</label>
                            <p style="font-weight:700;">${d.contact}</p>
                        </div>
                        <div>
                            <label style="display:block; font-size:10px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.25rem;">Government ID</label>
                            <p style="font-weight:700;">${d.gov_id || 'N/A'}</p>
                        </div>
                        <div>
                            <label style="display:block; font-size:10px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.25rem;">License Number</label>
                            <p style="font-weight:700;">${d.license_no}</p>
                        </div>
                         <div>
                            <label style="display:block; font-size:10px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.25rem;">Gender</label>
                            <p style="font-weight:700;">${d.gender || 'Not Specified'}</p>
                        </div>
                        <div>
                            <label style="display:block; font-size:10px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.25rem;">Applied On</label>
                            <p style="font-weight:700;">${d.formatted_date}</p>
                        </div>
                        <div style="grid-column: span 2;">
                            <label style="display:block; font-size:10px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.25rem;">Residence Address</label>
                            <p style="font-weight:700;">${d.address || 'Address not provided'}</p>
                        </div>
                        <div style="grid-column: span 2; margin-top:1rem; padding-top:1rem; border-top:1px solid #f3f4f6; display:flex; gap:1rem;">
                             <a href="?action=approve&id=${d.id}" class="btn btn-primary" style="flex:1; text-align:center;">Approve Application</a>
                             <a href="?action=reject&id=${d.id}" class="btn" style="flex:1; text-align:center; background:#fee2e2; color:#ef4444;">Reject</a>
                        </div>
                    `;
                } else {
                    body.innerHTML = `<div style="grid-column: span 2; color: #ef4444; font-weight:bold; text-align:center;">Error: ${res.message}</div>`;
                }
            });
    }

    function closeModal() {
        document.getElementById('detailsModal').style.display = 'none';
    }

    // Close on overlay click
    window.onclick = function(event) {
        const modal = document.getElementById('detailsModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

</body>
</html>
