<?php
// Shared public header for Driver Portal (Login/Application)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zuber Driver - Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Righteous&family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { logo: ['Righteous', 'cursive'], sans: ['Outfit', 'sans-serif'] },
                    colors: { primary: '#FF4B4B', success: '#22c55e' }
                }
            }
        }
    </script>
    <style>
        .hero-gradient { background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="hero-gradient min-h-screen font-sans text-white flex flex-col">

    <nav class="px-6 md:px-20 py-8 flex justify-between items-center relative z-[100]">
        <a href="/cab_app/index.php" class="text-3xl font-logo tracking-wider">Zuber <span class="text-primary text-sm font-sans font-black uppercase tracking-widest ml-2">Driver</span></a>
        
        <div class="flex items-center gap-8">
            <?php 
            $currentPage = basename($_SERVER['PHP_SELF']);
            if($currentPage == 'login.php'): ?>
                <a href="apply.php" class="text-sm font-bold text-gray-400 hover:text-white transition uppercase tracking-widest"><i class="fas fa-id-card mr-2"></i> Apply to Drive</a>
            <?php else: ?>
                <a href="login.php" class="text-sm font-bold text-gray-400 hover:text-white transition uppercase tracking-widest">Driver Login <i class="fas fa-arrow-right ml-2"></i></a>
            <?php endif; ?>
            
            <a href="/cab_app/index.php" class="hidden md:block text-[10px] font-black bg-white/5 border border-white/10 px-4 py-2 rounded-full hover:bg-white/10 transition grayscale opacity-50 hover:grayscale-0 hover:opacity-100 uppercase tracking-widest">
                Main Website
            </a>
        </div>
    </nav>
