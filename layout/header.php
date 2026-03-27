<?php
// Start session for state management and include DB configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../api/db_connect.php';

// Authentication state check
$isLoggedIn = isset($_SESSION['user_id']);
$userImage = 'https://cdn-icons-png.flaticon.com/512/847/847969.png'; // Default placeholder

function normalizeUserImagePath($path) {
    $path = trim((string)$path);
    if ($path === '') {
        return '';
    }

    if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, 'data:')) {
        return $path;
    }

    return '/cab_app/' . ltrim($path, '/');
}

// Fetch user-specific data if authenticated
if ($isLoggedIn) {
    $uid = $_SESSION['user_id'];
    $u_res = $conn->query("SELECT * FROM users WHERE id = $uid");
    if ($u_res && $u_data = $u_res->fetch_assoc()) {
        $userImage = !empty($u_data['profile_image']) ? normalizeUserImagePath($u_data['profile_image']) : $userImage;
        // Sync the latest name from DB to session for consistent display
        $_SESSION['user_name'] = $u_data['fullname'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zuber - Ride, Drive, and More</title>
    <!-- CSS Frameworks and Mapping Libraries (Leaflet.js) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Leaflet Routing Machine for trip path calculation -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <!-- Typography and Iconography -->
    <link href="https://fonts.googleapis.com/css2?family=Righteous&family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        // Custom Tailwind theme extensions
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { logo: ['Righteous', 'cursive'], sans: ['Outfit', 'sans-serif'] },
                    colors: { primary: '#FF4B4B' }
                }
            }
        }
    </script>
    <style>
        /* Modern dashboard-like clean UI */
        .glass-header { 
            background: #ffffff; 
            border-bottom: 1px solid #f1f5f9; 
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .nav-link { 
            position: relative; 
            color: #475569;
            transition: color 0.2s ease;
        }
        .nav-link:hover { color: #FF4B4B; }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Map Styles */
        #map { height: 600px; border-radius: 1rem; border: 1px solid #e2e8f0; }
        .leaflet-routing-container { display: none !important; }
        .leaflet-routing-alt-dist { white-space: nowrap !important; min-width: 60px !important; text-align: right !important; font-weight: bold !important; color: #475569; }

        /* Dropdown Menu Styles */
        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            width: 200px;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.2s ease;
            z-index: 6000;
        }
        .profile-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            transition: background-color 0.2s;
            cursor: pointer;
        }
        .dropdown-item:hover {
            background: #f8fafc;
            color: #0f172a;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 overflow-x-hidden">

    <!-- GLOBAL NAVIGATION: Clean dashboard-like white header -->
    <nav class="glass-header px-6 md:px-12 py-3 flex justify-center sticky top-0 z-[5000]">
        <div class="w-full max-w-[1400px] flex items-center justify-between">
            <div class="flex items-center gap-12">
                <a href="/cab_app/index.php" class="flex items-center gap-2 text-slate-900">
                    <h1 class="text-2xl font-bold tracking-tight">Zuber</h1>
                </a>
                <div class="hidden lg:flex items-center gap-8 text-[14px] font-medium">
                    <a href="/cab_app/index.php" class="nav-link">Home</a>
                    <a href="/cab_app/ride.php" class="nav-link">Ride</a>
                    <a href="/cab_app/driver/apply.php" class="nav-link">Drive</a>
                    <a href="/cab_app/about.php" class="nav-link">About us</a>
                    <a href="/cab_app/contact.php" class="nav-link">Contact</a>
                </div>
            </div>
            
            <!-- Dynamic authentication-based action area -->
            <div class="flex items-center gap-3 text-[14px] font-medium">
                <?php if(!$isLoggedIn): ?>
                    <a href="/cab_app/auth.php?mode=login" class="bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-50 transition-colors shadow-sm">Log in</a>
                    <a href="/cab_app/auth.php?mode=signup" class="bg-primary text-white border border-primary px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors shadow-sm">Sign up</a>
                <?php else: ?>
                    <div class="relative profile-container flex items-center gap-2 py-1">
                        <div onclick="toggleProfileMenu(event)" class="flex items-center gap-3 cursor-pointer hover:bg-slate-100 p-2 rounded-lg transition-colors">
                            <span class="hidden md:block text-slate-700 font-medium pointer-events-none"><?= $_SESSION['user_name'] ?></span>
                            <div class="w-9 h-9 rounded-full bg-slate-200 overflow-hidden pointer-events-none border border-slate-300">
                                <img src="<?= $userImage ?>" class="w-full h-full object-cover pointer-events-none" id="displayProfileImgNav">
                            </div>
                            <i class="fas fa-chevron-down text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>
                        
                        <!-- Dropdown Menu -->
                        <div class="profile-dropdown">
                            <a href="/cab_app/history.php" class="dropdown-item">
                                <i class="fas fa-history text-slate-400 w-4"></i>
                                <span>My Bookings</span>
                            </a>
                            <a href="/cab_app/account.php" class="dropdown-item">
                                <i class="fas fa-user-cog text-slate-400 w-4"></i>
                                <span>Manage Account</span>
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <a href="/cab_app/logout.php" class="dropdown-item text-red-600 hover:bg-red-50 hover:text-red-700">
                                <i class="fas fa-sign-out-alt text-red-400 w-4"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <script>
        function toggleProfileMenu(e) {
            e.stopPropagation();
            const dropdown = document.querySelector('.profile-dropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const dropdown = document.querySelector('.profile-dropdown');
            if (dropdown && dropdown.classList.contains('show')) {
                const container = document.querySelector('.profile-container');
                if (!container.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            }
        });
    </script>

