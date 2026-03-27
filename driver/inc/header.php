<?php
session_start();
require '../api/db_connect.php';

// Authentication Guard
if (!isset($_SESSION['driver_id'])) {
    header("Location: login.php");
    exit();
}

$driverId = $_SESSION['driver_id'];
$driverRes = $conn->query("SELECT * FROM drivers WHERE id = '$driverId'");
$driver = $driverRes->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zuber Driver - Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: { primary: '#FF4B4B', success: '#22c55e' }
                }
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
        #map { height: 100%; width: 100%; border-radius: 2rem; z-index: 1; }
        .pulse-online { animation: pulse 2s infinite; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); } 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); } }
    </style>
    <script>
        let driverStatus = "<?= $driver['status'] ?? 'offline' ?>";
        
        function updateStatusUI() {
            const dot = document.getElementById('statusDot');
            const text = document.getElementById('statusText');
            const btn = document.getElementById('statusBtn');
            const mIcon = document.getElementById('mobileStatusIcon');
            if(!dot || !text || !btn) return;

            if(driverStatus === 'available') {
                dot.className = "w-3 h-3 rounded-full bg-success pulse-online";
                text.innerText = "Online";
                text.className = "text-[10px] font-black uppercase tracking-widest text-success";
                btn.innerText = "Go Offline";
                btn.disabled = false;
                btn.className = "bg-red-50 text-red-500 border border-red-100 text-[9px] font-black px-4 py-2 rounded-xl uppercase tracking-widest transition-all";
                if(mIcon) mIcon.className = "fas fa-power-off text-success";
            } else if(driverStatus === 'on_trip') {
                dot.className = "w-3 h-3 rounded-full bg-orange-500";
                text.innerText = "On Trip";
                text.className = "text-[10px] font-black uppercase tracking-widest text-orange-500";
                btn.innerText = "Busy";
                btn.disabled = true;
                btn.className = "bg-slate-50 text-slate-400 border border-slate-100 text-[9px] font-black px-4 py-2 rounded-xl uppercase tracking-widest cursor-not-allowed";
            } else {
                dot.className = "w-3 h-3 rounded-full bg-gray-300";
                text.innerText = "Offline";
                text.className = "text-[10px] font-black uppercase tracking-widest text-gray-400";
                btn.innerText = "Go Online";
                btn.disabled = false;
                btn.className = "bg-blue-600 text-white shadow-lg shadow-blue-500/20 text-[9px] font-black px-4 py-2 rounded-xl uppercase tracking-widest transition-all";
                if(mIcon) mIcon.className = "fas fa-power-off text-gray-400";
            }
        }

        async function toggleStatus() {
            const newStatus = (driverStatus === "available" || driverStatus === "on_trip") ? "offline" : "available";
            if(driverStatus === "on_trip" && newStatus === "offline") {
                alert("Cannot go offline while on a trip!");
                return;
            }
            try {
                const res = await fetch('api/status.php', {
                    method: 'POST',
                    body: JSON.stringify({ status: newStatus })
                });
                const data = await res.json();
                if(data.success) {
                    driverStatus = newStatus;
                    updateStatusUI();
                    
                    // Call monitoring if on Dashboard
                    if(typeof startMonitoring === 'function' && typeof stopMonitoring === 'function') {
                        if(driverStatus === 'available') startMonitoring();
                        else stopMonitoring();
                    } else {
                        // If not on dashboard, maybe redirect to show effects or just stay
                        if(window.location.pathname.includes('index.php')) {
                           window.location.reload();
                        }
                    }
                }
            } catch(e) { console.error(e); }
        }
    </script>
</head>
<body class="bg-[#F8F9FA] font-sans text-black h-screen overflow-hidden flex">
