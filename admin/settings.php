<?php
include 'inc/header.php';
include 'inc/sidebar.php';

$message = $_SESSION['settings_flash_message'] ?? '';
$messageType = $_SESSION['settings_flash_type'] ?? 'success';
unset($_SESSION['settings_flash_message'], $_SESSION['settings_flash_type']);

$defaultSettings = [
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

$escapedDefaults = [];
foreach ($defaultSettings as $metaField => $metaValue) {
    $escapedDefaults[] = "('" . $conn->real_escape_string($metaField) . "', '" . $conn->real_escape_string($metaValue) . "')";
}

if (!empty($escapedDefaults)) {
    $conn->query("
        INSERT IGNORE INTO settings (meta_field, meta_value)
        VALUES " . implode(', ', $escapedDefaults)
    );
}

$settings = $defaultSettings;
$settingsResult = $conn->query("SELECT meta_field, meta_value FROM settings");

if ($settingsResult) {
    while ($row = $settingsResult->fetch_assoc()) {
        $settings[$row['meta_field']] = $row['meta_value'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $systemName = trim($_POST['system_name'] ?? '');
    $systemShortName = trim($_POST['system_short_name'] ?? '');
    $aboutContent = trim($_POST['about_content'] ?? '');
    $systemLogo = $settings['system_logo'] ?? '';

    if ($systemName === '' || $systemShortName === '' || $aboutContent === '') {
        $message = 'Fill in all required settings fields.';
        $messageType = 'danger';
    } else {
        if (isset($_FILES['system_logo']) && !empty($_FILES['system_logo']['name'])) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
            $originalName = $_FILES['system_logo']['name'];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            if (!in_array($extension, $allowedExtensions, true)) {
                $message = 'Upload a JPG, PNG, WEBP, or SVG logo.';
                $messageType = 'danger';
            } elseif ($_FILES['system_logo']['error'] !== UPLOAD_ERR_OK) {
                $message = 'Logo upload failed. Try again.';
                $messageType = 'danger';
            } else {
                $uploadDir = dirname(__DIR__) . '/uploads/settings/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $safeName = preg_replace('/[^A-Za-z0-9_-]/', '-', pathinfo($originalName, PATHINFO_FILENAME));
                $fileName = 'logo-' . time() . '-' . $safeName . '.' . $extension;
                $absolutePath = $uploadDir . $fileName;
                $relativePath = 'uploads/settings/' . $fileName;

                if (move_uploaded_file($_FILES['system_logo']['tmp_name'], $absolutePath)) {
                    $systemLogo = $relativePath;
                } else {
                    $message = 'Unable to save the uploaded logo.';
                    $messageType = 'danger';
                }
            }
        }

        if ($message === '') {
            $updatedSettings = [
                'system_name' => $systemName,
                'system_short_name' => $systemShortName,
                'about_content' => $aboutContent,
                'system_logo' => $systemLogo
            ];

            foreach ($updatedSettings as $metaField => $metaValue) {
                $metaFieldEscaped = $conn->real_escape_string($metaField);
                $metaValueEscaped = $conn->real_escape_string($metaValue);
                $exists = $conn->query("SELECT id FROM settings WHERE meta_field = '$metaFieldEscaped' LIMIT 1");

                if ($exists && $exists->num_rows > 0) {
                    $conn->query("UPDATE settings SET meta_value = '$metaValueEscaped' WHERE meta_field = '$metaFieldEscaped'");
                } else {
                    $conn->query("INSERT INTO settings (meta_field, meta_value) VALUES ('$metaFieldEscaped', '$metaValueEscaped')");
                }
            }

            $settings = array_merge($settings, $updatedSettings);
            $_SESSION['settings_flash_message'] = 'System settings updated successfully.';
            $_SESSION['settings_flash_type'] = 'success';
            header('Location: settings.php?saved=1');
            exit;
        } else {
            $settings['system_name'] = $systemName;
            $settings['system_short_name'] = $systemShortName;
            $settings['about_content'] = $aboutContent;
            $settings['system_logo'] = $systemLogo;
        }
    }
}

$logoPreview = !empty($settings['system_logo']) ? '../' . ltrim($settings['system_logo'], '/') : 'https://ui-avatars.com/api/?name=' . urlencode($settings['system_short_name']) . '&background=0f172a&color=fff&size=160';
?>

<main class="main-content settings-page">
    <style>
        .settings-page {
            background:
                radial-gradient(circle at top left, rgba(14, 165, 233, 0.14), transparent 28%),
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.12), transparent 22%),
                linear-gradient(180deg, #f8fbff 0%, #eef4ff 100%);
        }

        .settings-shell {
            max-width: 1320px;
            margin: 0 auto;
        }

        .settings-topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .settings-title-wrap h1 {
            font-size: 2.3rem;
            line-height: 1;
            margin-bottom: 0.75rem;
            color: #0f172a;
        }

        .settings-title-wrap p {
            max-width: 640px;
            color: #64748b;
            font-size: 0.98rem;
            line-height: 1.7;
        }

        .settings-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .save-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            background: linear-gradient(135deg, #0f172a 0%, #2563eb 100%);
            color: #fff;
            border: none;
            border-radius: 1.1rem;
            padding: 1rem 1.4rem;
            font-weight: 700;
            box-shadow: 0 18px 35px rgba(37, 99, 235, 0.18);
        }

        .save-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.2);
        }

        .settings-user-pill {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.8rem 0.9rem 0.8rem 1rem;
            background: rgba(255, 255, 255, 0.86);
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 1.25rem;
            box-shadow: 0 16px 30px rgba(148, 163, 184, 0.14);
        }

        .settings-user-pill small {
            display: block;
            color: #64748b;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.15rem;
        }

        .settings-user-pill strong {
            color: #0f172a;
            font-size: 1rem;
        }

        .settings-user-pill img {
            width: 42px;
            height: 42px;
            border-radius: 14px;
        }

        .settings-panel {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 1.8rem;
            box-shadow: 0 22px 45px rgba(148, 163, 184, 0.16);
        }

        .settings-form-card {
            padding: 1.7rem;
        }

        .settings-intro {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
            align-items: center;
        }

        .settings-intro h2 {
            font-size: 1.35rem;
            color: #0f172a;
            margin-bottom: 0.3rem;
        }

        .settings-intro p {
            color: #64748b;
            font-size: 0.92rem;
        }

        .settings-chip {
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 999px;
            padding: 0.5rem 0.85rem;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            white-space: nowrap;
        }

        .settings-alert {
            margin-bottom: 1.2rem;
            padding: 0.95rem 1rem;
            border-radius: 1rem;
            font-size: 0.92rem;
            font-weight: 600;
        }

        .settings-alert.success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .settings-alert.danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .settings-field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .settings-field {
            margin-bottom: 1rem;
        }

        .settings-field.full {
            grid-column: 1 / -1;
        }

        .settings-field label {
            display: block;
            margin-bottom: 0.55rem;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
            color: #64748b;
        }

        .settings-field input[type="text"],
        .settings-field textarea {
            width: 100%;
            border: 1px solid #dbe5f0;
            background: #f8fbff;
            border-radius: 1rem;
            padding: 0.95rem 1rem;
            outline: none;
            transition: 0.2s ease;
            color: #0f172a;
            font-size: 0.96rem;
        }

        .settings-field input[type="text"]:focus,
        .settings-field textarea:focus {
            border-color: #60a5fa;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.14);
        }

        .settings-field textarea {
            min-height: 180px;
            resize: vertical;
            line-height: 1.7;
        }

        .upload-shell {
            display: grid;
            grid-template-columns: 112px minmax(0, 1fr);
            gap: 1rem;
            align-items: center;
            padding: 1rem;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px dashed #cbd5e1;
            border-radius: 1.35rem;
        }

        .logo-preview {
            width: 112px;
            height: 112px;
            border-radius: 1.4rem;
            overflow: hidden;
            border: 1px solid #dbe5f0;
            background: #e2e8f0;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .logo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .upload-copy h3 {
            font-size: 1rem;
            color: #0f172a;
            margin-bottom: 0.35rem;
        }

        .upload-copy p {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 0.8rem;
        }

        .upload-copy input[type="file"] {
            width: 100%;
            background: #fff;
            border: 1px solid #dbe5f0;
            border-radius: 0.9rem;
            padding: 0.7rem 0.85rem;
        }

        @media (max-width: 780px) {
            .settings-topbar,
            .settings-intro {
                flex-direction: column;
                align-items: stretch;
            }

            .settings-actions {
                justify-content: flex-start;
            }

            .settings-field-grid,
            .upload-shell {
                grid-template-columns: 1fr;
            }

            .main-content.settings-page {
                padding: 1.25rem;
            }
        }
    </style>

    <div class="settings-shell">
        <div class="settings-topbar">
            <div class="settings-title-wrap">
                <h1>System Settings</h1>
                <p>Refine the platform identity, control the branding seen across the admin experience, and keep the public-facing message consistent from one place.</p>
            </div>

            <div class="settings-actions">
                <button type="submit" form="settingsForm" class="save-btn">
                    <i class="fas fa-save"></i>
                    <span>Save Changes</span>
                </button>

                <div class="settings-user-pill">
                    <div>
                        <small>Signed In</small>
                        <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['admin_username']); ?>&background=0f172a&color=fff" alt="Admin avatar">
                </div>
            </div>
        </div>

        <section class="settings-panel settings-form-card">
            <div class="settings-intro">
                <div>
                    <h2>Brand Configuration</h2>
                    <p>Update naming, the about copy, and the system logo used by the admin panel.</p>
                </div>
                <div class="settings-chip">Live Settings</div>
            </div>

            <?php if ($message !== ''): ?>
                <div class="settings-alert <?php echo $messageType === 'danger' ? 'danger' : 'success'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form id="settingsForm" method="POST" enctype="multipart/form-data">
                <div class="settings-field-grid">
                    <div class="settings-field">
                        <label for="system_name">System Name</label>
                        <input type="text" id="system_name" name="system_name" value="<?php echo htmlspecialchars($settings['system_name']); ?>" required>
                    </div>

                    <div class="settings-field">
                        <label for="system_short_name">System Short Name</label>
                        <input type="text" id="system_short_name" name="system_short_name" value="<?php echo htmlspecialchars($settings['system_short_name']); ?>" required>
                    </div>

                    <div class="settings-field full">
                        <label for="about_content">About Us Content</label>
                        <textarea id="about_content" name="about_content" required><?php echo htmlspecialchars($settings['about_content']); ?></textarea>
                    </div>

                    <div class="settings-field full">
                        <label for="system_logo">System Logo</label>
                        <div class="upload-shell">
                            <div class="logo-preview">
                                <img id="logoPreviewImage" src="<?php echo htmlspecialchars($logoPreview); ?>" alt="System logo preview">
                            </div>
                            <div class="upload-copy">
                                <h3>Upload a fresh brand mark</h3>
                                <p>Use a square image for the cleanest result. Supported formats: JPG, PNG, WEBP, SVG.</p>
                                <input type="file" id="system_logo" name="system_logo" accept=".jpg,.jpeg,.png,.webp,.svg,image/jpeg,image/png,image/webp,image/svg+xml">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>

    <script>
        const logoInput = document.getElementById('system_logo');
        const logoPreviewImage = document.getElementById('logoPreviewImage');

        if (logoInput) {
            logoInput.addEventListener('change', () => {
                const file = logoInput.files && logoInput.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (event) => {
                    logoPreviewImage.src = event.target.result;
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
</main>

</body>
</html>
