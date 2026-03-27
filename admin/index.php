<?php
include 'inc/header.php';
include 'inc/sidebar.php';

$defaultBrand = [
    'system_name' => 'Cab Booking Management System',
    'system_short_name' => 'CBMS',
    'about_content' => 'Welcome to the most efficient cab management system. We provide top-notch services for booking and managing rides.',
    'system_logo' => ''
];

$conn->query("
    CREATE TABLE IF NOT EXISTS settings (
        id INT(11) NOT NULL AUTO_INCREMENT,
        meta_field VARCHAR(100) NOT NULL,
        meta_value TEXT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY unique_meta_field (meta_field)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

$brand = $defaultBrand;
$brandResult = $conn->query("SELECT meta_field, meta_value FROM settings WHERE meta_field IN ('system_name', 'system_short_name', 'about_content', 'system_logo')");
if ($brandResult) {
    while ($row = $brandResult->fetch_assoc()) {
        $brand[$row['meta_field']] = $row['meta_value'];
    }
}

$brandLogo = !empty($brand['system_logo'])
    ? '../' . ltrim($brand['system_logo'], '/')
    : 'https://ui-avatars.com/api/?name=' . urlencode($brand['system_short_name']) . '&background=1e3a8a&color=fff&size=160';

// Summary Stats
$total_bookings = $conn->query("SELECT COUNT(id) FROM bookings")->fetch_row()[0];
$pending_bookings = $conn->query("SELECT COUNT(id) FROM bookings WHERE status = 'pending'")->fetch_row()[0];
$completed_trips = $conn->query("SELECT COUNT(id) FROM bookings WHERE status = 'completed'")->fetch_row()[0];
$registered_clients = $conn->query("SELECT COUNT(id) FROM users")->fetch_row()[0];
$total_drivers = $conn->query("SELECT COUNT(id) FROM drivers WHERE is_approved = 1")->fetch_row()[0];
$available_cabs = $conn->query("SELECT COUNT(id) FROM cabs WHERE status = 'available'")->fetch_row()[0];

$stats = [
    ['label' => 'Total Bookings', 'count' => $total_bookings, 'icon' => 'fas fa-calendar-alt', 'color' => '#6366f1'],
    ['label' => 'Pending Bookings', 'count' => $pending_bookings, 'icon' => 'fas fa-clock', 'color' => '#f59e0b'],
    ['label' => 'Completed Trips', 'count' => $completed_trips, 'icon' => 'fas fa-check-double', 'color' => '#10b981'],
    ['label' => 'Registered Clients', 'count' => $registered_clients, 'icon' => 'fas fa-users', 'color' => '#3b82f6'],
    ['label' => 'Drivers', 'count' => $total_drivers, 'icon' => 'fas fa-id-card', 'color' => '#8b5cf6'],
    ['label' => 'Available Cabs', 'count' => $available_cabs, 'icon' => 'fas fa-taxi', 'color' => '#ec4899'],
];

// Recent Bookings (Last 10)
$recent_bookings = $conn->query("SELECT b.*, u.fullname as customer 
                                FROM bookings b 
                                LEFT JOIN users u ON b.user_id = u.id 
                                ORDER BY b.id DESC LIMIT 10");
?>

<main class="main-content">
    <style>
        .identity-band {
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(280px, 0.7fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .dashboard-stats .stat-card {
            padding: 1rem 1.1rem;
            border-radius: 1.2rem;
            gap: 0.85rem;
            min-height: 108px;
        }

        .dashboard-stats .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 1rem;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .dashboard-stats .stat-info h3 {
            font-size: 0.8rem;
            line-height: 1.35;
            margin-bottom: 0.2rem;
        }

        .dashboard-stats .stat-info p {
            font-size: 1.2rem;
            line-height: 1;
        }

        .identity-card {
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
            color: #fff;
            border-radius: 1.75rem;
            padding: 1.6rem;
            box-shadow: 0 22px 45px rgba(37, 99, 235, 0.16);
            position: relative;
            overflow: hidden;
        }

        .identity-card::after {
            content: '';
            position: absolute;
            inset: auto -60px -90px auto;
            width: 220px;
            height: 220px;
            background: rgba(255,255,255,0.08);
            border-radius: 999px;
        }

        .identity-top {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .identity-logo {
            width: 72px;
            height: 72px;
            border-radius: 1.3rem;
            overflow: hidden;
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .identity-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .identity-short {
            display: inline-block;
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.16);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.55rem;
        }

        .identity-card h2 {
            font-size: 1.9rem;
            line-height: 1.15;
            margin-bottom: 0.55rem;
            position: relative;
            z-index: 1;
        }

        .identity-card p {
            color: rgba(255,255,255,0.8);
            line-height: 1.7;
            max-width: 720px;
            position: relative;
            z-index: 1;
        }

        .identity-side {
            background: white;
            border-radius: 1.75rem;
            padding: 1.4rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .identity-side h3 {
            color: #0f172a;
            font-size: 1.05rem;
            margin-bottom: 1rem;
        }

        .identity-meta {
            display: grid;
            gap: 0.85rem;
        }

        .identity-meta-item {
            padding: 0.95rem 1rem;
            border-radius: 1.1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .identity-meta-item span {
            display: block;
            color: #64748b;
            font-size: 0.74rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.35rem;
        }

        .identity-meta-item strong {
            color: #0f172a;
            font-size: 1rem;
            word-break: break-word;
        }

        @media (max-width: 1080px) {
            .identity-band {
                grid-template-columns: 1fr;
            }

            .dashboard-stats {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 780px) {
            .dashboard-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 520px) {
            .dashboard-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="top-bar">
        <h1>Dashboard Overview</h1>
        <div class="user-info">
            <span>Welcome, <strong><?php echo $_SESSION['admin_username']; ?></strong></span>
            <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Admin" style="width: 32px; height: 32px; border-radius: 50%;">
        </div>
    </div>

    <div class="identity-band">
        <section class="identity-card">
            <div class="identity-top">
                <div class="identity-logo">
                    <img src="<?php echo htmlspecialchars($brandLogo); ?>" alt="System identity">
                </div>
                <div>
                    <span class="identity-short"><?php echo htmlspecialchars($brand['system_short_name']); ?></span>
                    <h2><?php echo htmlspecialchars($brand['system_name']); ?></h2>
                </div>
            </div>
            <p><?php echo htmlspecialchars($brand['about_content']); ?></p>
        </section>

        <aside class="identity-side">
            <h3>Identity Snapshot</h3>
            <div class="identity-meta">
                <div class="identity-meta-item">
                    <span>System Name</span>
                    <strong><?php echo htmlspecialchars($brand['system_name']); ?></strong>
                </div>
                <div class="identity-meta-item">
                    <span>Short Label</span>
                    <strong><?php echo htmlspecialchars($brand['system_short_name']); ?></strong>
                </div>
            </div>
        </aside>
    </div>

    <div class="stats-grid dashboard-stats">
        <?php foreach ($stats as $stat): ?>
            <div class="stat-card">
                <div class="stat-icon" style="background: <?php echo $stat['color']; ?>;">
                    <i class="<?php echo $stat['icon']; ?>"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stat['label']; ?></h3>
                    <p><?php echo $stat['count']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2>Recent Bookings</h2>
            <a href="bookings.php" class="btn btn-primary">View All</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Route</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recent_bookings->num_rows > 0): ?>
                    <?php while($row = $recent_bookings->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['customer'] ?? 'Guest'); ?></td>
                            <td><?php echo htmlspecialchars($row['pickup_location']); ?> → <?php echo htmlspecialchars($row['destination']); ?></td>
                            <td>
                                <span class="badge badge-<?php 
                                    $stat = !empty($row['status']) ? $row['status'] : 'pending';
                                    echo $stat == 'completed' ? 'success' : ($stat == 'pending' ? 'warning' : ($stat == 'confirmed' ? 'primary' : 'danger')); 
                                ?>">
                                    <?php echo !empty($row['status']) ? ucfirst($row['status']) : 'Unknown'; ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, H:i', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="booking_details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Details</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center; padding: 2rem; color: #6b7280;">No recent bookings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
