<?php
// Fetch Earnings
$earningsRes = $conn->query("SELECT SUM(fare) as total FROM bookings WHERE driver_id = '$driverId' AND status = 'completed'");
$earnings = $earningsRes->fetch_assoc()['total'] ?? 0;

// Fetch Completed Rides count
$ridesRes = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE driver_id = '$driverId' AND status = 'completed'");
$rideCount = $ridesRes->fetch_assoc()['count'];
?>
<aside class="w-80 bg-white border-r border-gray-100 p-8 flex flex-col justify-between hidden lg:flex">
    <div>
        <div class="mb-12">
            <h1 class="text-2xl font-black tracking-tighter">ZUBER <span class="text-primary text-xs font-bold uppercase tracking-[0.2em] ml-2">Partner</span></h1>
        </div>

        <div class="space-y-2">
            <div class="p-6 bg-gray-50 rounded-[2rem] mb-8">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Earnings</p>
                <h2 class="text-3xl font-black">₹<?= number_format($earnings, 2) ?></h2>
                <div class="flex items-center gap-2 mt-4 text-[11px] font-bold text-gray-500">
                    <span class="bg-primary/10 text-primary px-2 py-1 rounded-lg"><?= $rideCount ?> Rides</span>
                    <span>Completed</span>
                </div>
            </div>

            <nav class="space-y-1">
                <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
                <a href="index.php" class="flex items-center gap-4 p-4 <?= $currentPage == 'index.php' ? 'bg-blue-50 text-blue-600 border border-blue-100/50' : 'text-gray-400 hover:text-slate-600 hover:bg-slate-50' ?> rounded-2xl font-bold transition-all group">
                    <i class="fas fa-grid-2 <?= $currentPage == 'index.php' ? 'text-blue-600' : 'text-gray-300 group-hover:text-slate-400' ?>"></i> Dashboard
                </a>
                <a href="trips.php" class="flex items-center gap-4 p-4 <?= $currentPage == 'trips.php' ? 'bg-blue-50 text-blue-600 border border-blue-100/50' : 'text-gray-400 hover:text-slate-600 hover:bg-slate-50' ?> rounded-2xl font-bold transition-all group">
                    <i class="fas fa-clock-rotate-left <?= $currentPage == 'trips.php' ? 'text-blue-600' : 'text-gray-300 group-hover:text-slate-400' ?>"></i> Trip History
                </a>
                <a href="profile.php" class="flex items-center gap-4 p-4 <?= $currentPage == 'profile.php' ? 'bg-blue-50 text-blue-600 border border-blue-100/50' : 'text-gray-400 hover:text-slate-600 hover:bg-slate-50' ?> rounded-2xl font-bold transition-all group">
                    <i class="fas fa-user-circle <?= $currentPage == 'profile.php' ? 'text-blue-600' : 'text-gray-300 group-hover:text-slate-400' ?>"></i> Profile
                </a>
            </nav>
        </div>
    </div>

    <div class="space-y-4">
        <?php
        $status = $driver['status'] ?? 'offline';
        $dotClass = "bg-gray-300";
        $textClass = "text-gray-400";
        $labelText = "Offline";
        $btnText = "Go Online";
        $btnClass = "bg-blue-600 text-white shadow-lg shadow-blue-500/20";
        $isDisabled = "";

        if ($status === 'available') {
            $dotClass = "bg-success pulse-online";
            $textClass = "text-success";
            $labelText = "Online";
            $btnText = "Go Offline";
            $btnClass = "bg-red-50 text-red-500 border border-red-100";
        } elseif ($status === 'on_trip') {
            $dotClass = "bg-orange-500";
            $textClass = "text-orange-500";
            $labelText = "On Trip";
            $btnText = "Busy";
            $btnClass = "bg-slate-50 text-slate-400 cursor-not-allowed border border-slate-100";
            $isDisabled = "disabled";
        }
        ?>
        <div id="statusIndicator" class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div id="statusDot" class="w-3 h-3 rounded-full <?= $dotClass ?>"></div>
                <span id="statusText" class="text-[10px] font-black uppercase tracking-widest <?= $textClass ?>"><?= $labelText ?></span>
            </div>
            <button onclick="toggleStatus()" id="statusBtn" <?= $isDisabled ?> class="<?= $btnClass ?> text-[9px] font-black px-4 py-2 rounded-xl uppercase tracking-widest transition-all">
                <?= $btnText ?>
            </button>
        </div>
        <a href="logout.php" class="flex items-center gap-4 p-4 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-2xl font-bold transition-all group">
            <i class="fas fa-sign-out-alt text-gray-300 group-hover:text-red-400"></i> Logout
        </a>
    </div>
</aside>
