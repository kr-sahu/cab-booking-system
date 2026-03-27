<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-taxi"></i>
        <span>CBMS Admin</span>
    </div>
    <nav>
        <a href="index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>

        <a href="cabs.php" class="nav-link <?php echo $current_page == 'cabs.php' ? 'active' : ''; ?>">
            <i class="fas fa-car"></i>
            <span>Cab Management</span>
        </a>
        <a href="driver_applications.php" class="nav-link <?php echo $current_page == 'driver_applications.php' ? 'active' : ''; ?>">
            <i class="fas fa-file-signature"></i>
            <span>Driver Applications</span>
        </a>
        <a href="drivers.php" class="nav-link <?php echo $current_page == 'drivers.php' ? 'active' : ''; ?>">
            <i class="fas fa-id-card"></i>
            <span>Driver Management</span>
        </a>
        <a href="bookings.php" class="nav-link <?php echo $current_page == 'bookings.php' ? 'active' : ''; ?>">
            <i class="fas fa-calendar-check"></i>
            <span>Booking Management</span>
        </a>
        <a href="clients.php" class="nav-link <?php echo $current_page == 'clients.php' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>
            <span>Client Management</span>
        </a>
        <a href="support_contacts.php" class="nav-link <?php echo $current_page == 'support_contacts.php' ? 'active' : ''; ?>">
            <i class="fas fa-headset"></i>
            <span>Support Contacts</span>
        </a>
        <a href="users.php" class="nav-link <?php echo $current_page == 'users.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-shield"></i>
            <span>System Users</span>
        </a>
        <a href="settings.php" class="nav-link <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a>
        <div style="margin-top: 2rem; border-top: 1px solid #374151; padding-top: 1rem;">
            <a href="logout.php" class="nav-link" style="color: #f87171;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</aside>
