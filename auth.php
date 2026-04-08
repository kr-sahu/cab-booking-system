<?php
// Initialize session to track user login status
session_start();
// Database connection for user verification and registration
require 'api/db_connect.php';

$msg = "";
$loginData = [
    'email' => '',
    'password' => ''
];
$signupData = [
    'name' => '',
    'phone' => '',
    'email' => ''
];
// Handle incoming POST requests for Login and Signup
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Logic for User Registration (Signup)
        if (isset($_POST['signup'])) {
            $name = trim($_POST['name'] ?? '');
            $emailInput = trim($_POST['email'] ?? '');
            $phoneInput = trim($_POST['phone'] ?? '');
            $passwordInput = $_POST['password'] ?? '';

            $signupData['name'] = $name;
            $signupData['email'] = strtolower($emailInput);
            $signupData['phone'] = $phoneInput;

            $normalizedEmail = strtolower($emailInput);
            $phoneDigits = preg_replace('/\D/', '', $phoneInput);

            if (str_starts_with($phoneDigits, '91') && strlen($phoneDigits) === 12) {
                $phoneDigits = substr($phoneDigits, 2);
            }

            if (!preg_match('/^[A-Za-z ]{2,60}$/', $name)) {
                $msg = "Enter a valid full name.";
            } elseif (!preg_match('/@gmail\.com$/', $normalizedEmail)) {
                $msg = "Email must end with @gmail.com.";
            } elseif (!preg_match('/^\d{10}$/', $phoneDigits)) {
                $msg = "Enter exactly 10 digits for the phone number.";
            } elseif (strlen($passwordInput) < 8) {
                $msg = "Password must be at least 8 characters.";
            } else {
                $name = $conn->real_escape_string($name);
                $email = $conn->real_escape_string($normalizedEmail);
                $phone = $conn->real_escape_string('+91 ' . $phoneDigits);
                $pass = password_hash($passwordInput, PASSWORD_DEFAULT);
                
                // Check if the email is already registered in the system
                $check = $conn->query("SELECT id FROM users WHERE email='$email'");
                if ($check->num_rows > 0) {
                    $msg = "Email already registered!";
                } else {
                    // Insert new user into the database with phone and secured password hash
                    $sql = "INSERT INTO users (fullname, email, phone, password) VALUES ('$name', '$email', '$phone', '$pass')";
                    if ($conn->query($sql)) {
                        $signupData = ['name' => '', 'phone' => '', 'email' => ''];
                        $msg = "<span class='text-green-600'>Account created! Please login.</span>";
                    } else { $msg = "Error creating account."; }
                }
            }
        } 
    // Logic for User Authentication (Login)
    elseif (isset($_POST['login'])) {
        $loginData['email'] = trim($_POST['email'] ?? '');
        $loginData['password'] = $_POST['password'] ?? '';

        $email = $conn->real_escape_string($loginData['email']);
        $password = $loginData['password'];
        $statusColumnExists = false;
        $statusColumnCheck = $conn->query("SHOW COLUMNS FROM users LIKE 'is_active'");

        if ($statusColumnCheck && $statusColumnCheck->num_rows > 0) {
            $statusColumnExists = true;
        }

        $result = $conn->query("SELECT * FROM users WHERE email='$email'");
        
        // Validate credentials against stored password hash
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($statusColumnExists && isset($row['is_active']) && (int) $row['is_active'] !== 1) {
                $msg = "Your account has been blocked by admin.";
            } elseif (password_verify($password, $row['password'])) {
                // Set session variables upon successful authentication
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['fullname'];
                header("Location: index.php");
                exit();
            }
        }

        if (!$msg) {
            $msg = "Invalid credentials";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zuber - Welcome</title>
    <!-- External styling and icon libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Righteous&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind Configuration for custom theme colors and fonts -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { logo: ['Righteous', 'cursive'], sans: ['Outfit', 'sans-serif'] },
                    colors: { primary: '#FF4B4B' }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 min-h-screen font-sans text-slate-800 flex flex-col relative overflow-x-hidden">

    <!-- Decorative dashboard background elements -->
    <div class="fixed top-0 inset-x-0 h-64 bg-slate-800 -z-10"></div>
    <div class="fixed top-0 inset-x-0 h-full bg-gradient-to-b from-transparent to-slate-50 translate-y-32 -z-10"></div>

    <!-- NAVIGATION: Minimal header with branding and back-to-home link -->
    <nav class="w-full max-w-[1400px] mx-auto px-6 md:px-12 py-8 flex justify-between items-center text-white">
        <a href="index.php" class="text-3xl font-logo tracking-wider">Zuber</a>
        <a href="index.php" class="text-[13px] font-bold text-slate-300 hover:text-white transition-colors uppercase tracking-widest"><i class="fas fa-arrow-left mr-2"></i> Back Home</a>
    </nav>

    <!-- AUTHENTICATION CARDS: Centered container for login and signup forms -->
    <main class="flex-1 flex items-center justify-center p-6 -mt-10">
        <div class="w-full max-w-[420px]">
            <div class="bg-white rounded-[1.5rem] shadow-[0_8px_30px_-4px_rgba(0,0,0,0.1)] border border-slate-200 p-8 sm:p-10 relative">
                
                <!-- Display dynamic success or error messages -->
                <?php if($msg): ?>
                    <div class="bg-red-50 text-red-600 p-3.5 rounded-xl text-center mb-6 text-sm font-semibold border border-red-100">
                        <?= $msg ?>
                    </div>
                <?php endif; ?>

                <!-- LOGIN FORM: Default view for returning users -->
                <form id="loginForm" method="POST" class="space-y-5 <?= isset($_GET['mode']) && $_GET['mode'] === 'signup' ? 'hidden' : '' ?>">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-extrabold text-slate-900">Welcome Back</h3>
                        <p class="text-slate-500 mt-1.5 font-medium text-sm">Log in to your account</p>
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1 block">Email Address</label>
                        <input type="email" name="email" placeholder="name@email.com" required value="<?= htmlspecialchars($loginData['email']) ?>"
                            class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all text-sm font-semibold text-slate-800 placeholder-slate-400">
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1 block">Password</label>
                        <input type="password" name="password" placeholder="••••••••" required 
                            class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all text-sm font-semibold text-slate-800 placeholder-slate-400">
                    </div>
                    
                    <button type="submit" name="login" class="w-full bg-slate-800 text-white p-4 rounded-xl font-bold text-[15px] hover:bg-slate-900 transition-colors shadow-md mt-2">
                        Log In
                    </button>
                    
                    <p class="text-center text-slate-500 text-[13px] font-medium mt-6">
                        New to Zuber? <span onclick="toggleAuth('signup')" class="text-primary font-bold cursor-pointer hover:underline">Create Account</span>
                    </p>
                </form>

                <!-- SIGNUP FORM: View for first-time user registration -->
                <form id="signupForm" method="POST" class="space-y-5 <?= isset($_GET['mode']) && $_GET['mode'] === 'signup' ? '' : 'hidden' ?>">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-extrabold text-slate-900">Create Account</h3>
                        <p class="text-slate-500 mt-1.5 font-medium text-sm">Join the Zuber platform</p>
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1 block">Full Name</label>
                        <input type="text" name="name" placeholder="John Doe" required value="<?= htmlspecialchars($signupData['name']) ?>"
                            class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all text-sm font-semibold text-slate-800 placeholder-slate-400">
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1 block">Phone Number</label>
                        <input type="tel" id="signupPhone" name="phone" placeholder="+91 9876543210" required inputmode="numeric" autocomplete="tel-national"
                            value="<?= htmlspecialchars($signupData['phone']) ?>"
                            maxlength="14" pattern="^\+91\s\d{10}$" title="Enter exactly 10 digits"
                            class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all text-sm font-semibold text-slate-800 placeholder-slate-400">
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1 block">Email Address</label>
                        <input type="text" id="signupEmail" name="email" placeholder="anything@gmail.com" required value="<?= htmlspecialchars($signupData['email']) ?>"
                            pattern=".*@gmail\.com$" title="Email must end with @gmail.com"
                            class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all text-sm font-semibold text-slate-800 placeholder-slate-400">
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1 block">Create Password</label>
                        <input type="password" name="password" placeholder="••••••••" required 
                            class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all text-sm font-semibold text-slate-800 placeholder-slate-400">
                    </div>
                    
                    <button type="submit" name="signup" class="w-full bg-slate-800 text-white p-4 rounded-xl font-bold text-[15px] hover:bg-slate-900 transition-colors shadow-md mt-2">
                        Sign Up
                    </button>
                    
                    <p class="text-center text-slate-500 text-[13px] font-medium mt-6">
                        Already have an account? <span onclick="toggleAuth('login')" class="text-primary font-bold cursor-pointer hover:underline">Log in</span>
                    </p>
                </form>

            </div>
        </div>
    </main>

    <footer class="p-6 text-center text-slate-500 text-[10px] uppercase tracking-widest font-bold">
        &copy; 2026 Zuber. Developed by Project Team
    </footer>

    <!-- CLIENT-SIDE LOGIC: Toggles between Login and Signup form visibility -->
    <script>
        function toggleAuth(type) {
            const login = document.getElementById('loginForm');
            const signup = document.getElementById('signupForm');
            const url = new URL(window.location);
            
            if (type === 'signup') {
                login.classList.add('hidden');
                signup.classList.remove('hidden');
                url.searchParams.set('mode', 'signup');
            } else {
                login.classList.remove('hidden');
                signup.classList.add('hidden');
                url.searchParams.set('mode', 'login');
            }
            window.history.pushState({}, '', url);
        }

        const signupPhone = document.getElementById('signupPhone');
        const signupEmail = document.getElementById('signupEmail');
        const loginPassword = <?= json_encode($loginData['password']) ?>;
        const loginPasswordInput = document.querySelector('#loginForm input[name="password"]');

        if (loginPasswordInput && loginPassword) {
            loginPasswordInput.value = loginPassword;
        }

        if (signupPhone) {
            const phonePrefix = '+91 ';

            const normalizePhone = () => {
                let digits = signupPhone.value.replace(/\D/g, '');

                if (digits.startsWith('91')) {
                    digits = digits.slice(2);
                }

                digits = digits.slice(0, 10);
                signupPhone.value = phonePrefix + digits;
            };

            signupPhone.addEventListener('focus', () => {
                if (!signupPhone.value.trim()) {
                    signupPhone.value = phonePrefix;
                }
            });

            signupPhone.addEventListener('input', normalizePhone);

            signupPhone.addEventListener('keydown', (event) => {
                const prefixLength = phonePrefix.length;
                const cursorStart = signupPhone.selectionStart ?? 0;

                if ((event.key === 'Backspace' && cursorStart <= prefixLength) ||
                    (event.key === 'Delete' && cursorStart < prefixLength)) {
                    event.preventDefault();
                }
            });

            signupPhone.addEventListener('blur', () => {
                if (signupPhone.value === phonePrefix) {
                    signupPhone.value = '';
                }
            });
        }

        if (signupEmail) {
            signupEmail.addEventListener('input', () => {
                signupEmail.value = signupEmail.value.trim().toLowerCase();
            });
        }
    </script>
</body>
</html>
