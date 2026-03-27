<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/api/db_connect.php';

$supportContacts = [
    'support_email' => 'support@zuber.com',
    'support_phone' => '+91 1800-ZUBER-00'
];

$contactMessage = $_SESSION['contact_flash_message'] ?? '';
$contactMessageType = $_SESSION['contact_flash_type'] ?? 'success';
unset($_SESSION['contact_flash_message'], $_SESSION['contact_flash_type']);

$contactFormData = $_SESSION['contact_form_data'] ?? [
    'full_name' => '',
    'email' => '',
    'subject' => '',
    'message' => ''
];
unset($_SESSION['contact_form_data']);

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $_SESSION['contact_form_data'] = [
        'full_name' => $fullName,
        'email' => $email,
        'subject' => $subject,
        'message' => $message
    ];

    if ($fullName === '' || $email === '' || $subject === '' || $message === '') {
        $_SESSION['contact_flash_message'] = 'Fill in all contact form fields.';
        $_SESSION['contact_flash_type'] = 'danger';
    } elseif (!preg_match('/@gmail\.com$/i', $email)) {
        $_SESSION['contact_flash_message'] = 'Email must end with @gmail.com.';
        $_SESSION['contact_flash_type'] = 'danger';
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_messages (full_name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fullName, $email, $subject, $message);
        $stmt->execute();

        $_SESSION['contact_flash_message'] = 'Message sent successfully.';
        $_SESSION['contact_flash_type'] = 'success';
        $_SESSION['contact_form_data'] = [
            'full_name' => '',
            'email' => '',
            'subject' => '',
            'message' => ''
        ];
    }

    header('Location: contact.php');
    exit;
}

// Include the global header for site-wide navigation
include 'layout/header.php'; 
?>

    <!-- CONTACT SECTION: Clean dashboard-like layout -->
    <main class="py-16 px-6 md:px-12 bg-slate-50 min-h-[85vh] flex items-center justify-center">
        <div class="max-w-[1400px] w-full mx-auto">
            
            <div class="mb-12 text-center">
                <div class="inline-block px-4 py-1.5 bg-primary/10 border border-primary/20 rounded-full mb-4">
                    <span class="text-[12px] font-bold text-primary uppercase tracking-widest">Get in touch</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight">We're here to help.</h2>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-8 items-start text-left max-w-5xl mx-auto">
                <!-- Info Column -->
                <div class="bg-white p-10 rounded-[2rem] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] border border-slate-100">
                    <div class="space-y-10">
                        <div>
                            <h4 class="text-2xl font-extrabold text-slate-800 mb-2">Contact Info</h4>
                            <p class="text-slate-500 font-medium">Have questions or need support? Our team is available 24/7 to assist you.</p>
                        </div>
                        <div class="space-y-6">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-primary shadow-inner border border-slate-100"><i class="fas fa-envelope text-lg"></i></div>
                                <div>
                                    <p class="text-[10px] font-extrabold text-[#94a3b8] uppercase tracking-widest mb-0.5">Email us at</p>
                                    <p class="text-[15px] font-bold text-slate-800"><?= htmlspecialchars($supportContacts['support_email']) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-primary shadow-inner border border-slate-100"><i class="fas fa-phone-alt text-lg"></i></div>
                                <div>
                                    <p class="text-[10px] font-extrabold text-[#94a3b8] uppercase tracking-widest mb-0.5">Call us at</p>
                                    <p class="text-[15px] font-bold text-slate-800"><?= htmlspecialchars($supportContacts['support_phone']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Column -->
                <div class="bg-white p-10 rounded-[2rem] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] border border-slate-100">
                    <?php if ($contactMessage): ?>
                        <div class="mb-5 px-4 py-3 rounded-xl text-sm font-semibold border <?= $contactMessageType === 'danger' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-green-50 text-green-700 border-green-100' ?>">
                            <?= htmlspecialchars($contactMessage) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1 block">Full Name</label>
                                <input type="text" name="full_name" placeholder="Your Name" value="<?= htmlspecialchars($contactFormData['full_name']) ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all text-sm font-semibold text-slate-800 placeholder-slate-400">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1 block">Email Address</label>
                                <input type="text" name="email" placeholder="anything@gmail.com" value="<?= htmlspecialchars($contactFormData['email']) ?>" pattern=".*@gmail\.com$" title="Email must end with @gmail.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all text-sm font-semibold text-slate-800 placeholder-slate-400">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1 block">Subject</label>
                            <input type="text" name="subject" placeholder="How can we help?" value="<?= htmlspecialchars($contactFormData['subject']) ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all text-sm font-semibold text-slate-800 placeholder-slate-400">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1 block">Message</label>
                            <textarea rows="4" name="message" placeholder="Tell us more about your inquiry..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all text-sm font-semibold text-slate-800 placeholder-slate-400 resize-none"><?= htmlspecialchars($contactFormData['message']) ?></textarea>
                        </div>
                        <button type="submit" class="w-full bg-slate-800 text-white py-4 rounded-xl font-bold text-sm shadow-md hover:bg-slate-900 transition-colors mt-2">Send Message</button>
                    </form>
                </div>
            </div>
            
        </div>
    </main>

<?php 
// Include the global footer
include 'layout/footer.php'; 
?>
