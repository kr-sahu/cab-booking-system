<?php
include 'inc/header.php';
include 'inc/sidebar.php';

$trips = $conn->query("SELECT * FROM bookings WHERE driver_id = '$driverId' ORDER BY created_at DESC");
?>

<main class="flex-1 p-8 overflow-y-auto no-scrollbar bg-white">
    <div class="max-w-[1200px] mx-auto">
        <header class="mb-12 flex justify-between items-end">
            <div>
                <h3 class="text-xs font-bold text-primary uppercase tracking-[0.4em] mb-2">Portfolio</h3>
                <h2 class="text-5xl font-black">Trip History<span class="text-primary">.</span></h2>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Lifetime Earnings</p>
                <p class="text-3xl font-black">₹<?= number_format($earnings, 2) ?></p>
            </div>
        </header>

        <div class="table-container">
            <table class="w-full text-left border-separate border-spacing-y-4">
                <thead>
                    <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <th class="px-8 py-4">Date</th>
                        <th class="px-8 py-4">Customer</th>
                        <th class="px-8 py-4">Route</th>
                        <th class="px-8 py-4 text-right">Fare</th>
                        <th class="px-8 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($trips->num_rows > 0): ?>
                        <?php while($row = $trips->fetch_assoc()): ?>
                            <tr class="bg-gray-50/50 hover:bg-gray-50 transition-all rounded-[2rem]">
                                <td class="px-8 py-6 rounded-l-[2rem]">
                                    <p class="font-bold text-sm"><?= date('M d, Y', strtotime($row['created_at'])) ?></p>
                                    <p class="text-[10px] text-gray-400 font-bold"><?= date('h:i A', strtotime($row['created_at'])) ?></p>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="font-black"><?= $row['driver_name'] ? 'Zuber Client' : 'Pending' ?></p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Standard Trip</p>
                                </td>
                                <td class="px-8 py-6 max-w-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-bold text-gray-400">From:</span>
                                        <span class="text-xs font-bold truncate"><?= $row['pickup_location'] ?></span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] font-bold text-primary">To:</span>
                                        <span class="text-xs font-bold truncate"><?= $row['destination'] ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <p class="font-black text-lg text-black">₹<?= number_format($row['fare'], 2) ?></p>
                                </td>
                                <td class="px-8 py-6 text-center rounded-r-[2rem]">
                                    <span class="bg-<?= $row['status'] == 'completed' ? 'green-500' : ($row['status'] == 'cancelled' ? 'red-500' : 'orange-500') ?> text-white text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="py-20 text-center text-gray-400 font-bold">No trips found in your account.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

    <script>
        window.onload = () => {
            updateStatusUI();
        };
    </script>
</body>
</html>
