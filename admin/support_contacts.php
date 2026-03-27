<?php
include 'inc/header.php';
include 'inc/sidebar.php';

$conn->query("
    CREATE TABLE IF NOT EXISTS contact_messages (
        id INT(11) NOT NULL AUTO_INCREMENT,
        full_name VARCHAR(120) NOT NULL,
        email VARCHAR(190) NOT NULL,
        subject VARCHAR(190) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC, id DESC");
$messageCount = $messages ? $messages->num_rows : 0;
?>

<main class="main-content">
    <style>
        .support-shell {
            max-width: 1240px;
            margin: 0 auto;
        }

        .support-overview {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(300px, 0.8fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .support-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1.6rem;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .support-hero {
            padding: 1.8rem;
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
            color: #fff;
        }

        .support-hero span {
            display: inline-block;
            margin-bottom: 1rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.18);
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.09em;
            text-transform: uppercase;
        }

        .support-hero h1 {
            font-size: 2rem;
            margin-bottom: 0.65rem;
        }

        .support-hero p {
            color: rgba(255, 255, 255, 0.78);
            line-height: 1.7;
            max-width: 640px;
        }

        .summary-card {
            padding: 1.5rem;
        }

        .summary-card h3 {
            color: #0f172a;
            font-size: 1.05rem;
            margin-bottom: 1rem;
        }

        .summary-stat {
            padding: 1rem;
            border-radius: 1.1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .summary-stat span {
            display: block;
            color: #64748b;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .summary-stat strong {
            color: #0f172a;
            font-size: 1.8rem;
        }

        .latest-message {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 1.1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .latest-message small {
            display: block;
            color: #64748b;
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
            margin-bottom: 0.45rem;
        }

        .latest-message strong {
            color: #0f172a;
            font-size: 1rem;
        }

        .latest-message p {
            color: #475569;
            line-height: 1.6;
            margin-top: 0.55rem;
        }

        .empty-state {
            padding: 3rem 1.5rem;
            text-align: center;
            color: #64748b;
        }

        .empty-state i {
            font-size: 2rem;
            color: #94a3b8;
            margin-bottom: 0.8rem;
        }

        .messages-table {
            width: 100%;
            border-collapse: collapse;
        }

        .messages-table th,
        .messages-table td {
            padding: 1rem 1.1rem;
            text-align: left;
            vertical-align: top;
            border-bottom: 1px solid #e2e8f0;
        }

        .messages-table td p {
            color: #475569;
            line-height: 1.6;
            max-width: 520px;
            margin-top: 0.35rem;
        }

        .messages-table td small {
            color: #64748b;
            display: block;
            margin-top: 0.25rem;
        }

        @media (max-width: 980px) {
            .support-overview {
                grid-template-columns: 1fr;
            }

            .messages-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>

    <div class="support-shell">
        <div class="top-bar">
            <h1>Support Contacts</h1>
            <div class="user-info">
                <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></span>
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['admin_username']); ?>&background=6366f1&color=fff" alt="Admin" style="width: 32px; height: 32px; border-radius: 50%;">
            </div>
        </div>

        <div class="support-overview">
            <section class="support-card support-hero">
                <span>Overview</span>
                <h1>User support messages</h1>
                <p>This section stores only the messages sent by users from the public contact page. Support email and phone values are not managed here.</p>
            </section>

            <aside class="support-card summary-card">
                <h3>Overview</h3>
                <div class="summary-stat">
                    <span>Total Messages</span>
                    <strong><?php echo (int) $messageCount; ?></strong>
                </div>
            </aside>
        </div>

        <section class="support-card">
            <?php if ($messages && $messageCount > 0): ?>
                <table class="messages-table">
                    <thead>
                        <tr>
                            <th>Sender</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $messages->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['full_name']); ?></strong>
                                    <small><?php echo htmlspecialchars($row['email']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td><p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p></td>
                                <td><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No user messages have been submitted yet.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>

</body>
</html>
