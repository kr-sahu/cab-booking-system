<?php 
include 'layout/header.php'; 

// Guard access for signed-in users only.
if (!$isLoggedIn) {
    header("Location: auth.php?mode=login");
    exit();
}
?>

<div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.10),_transparent_22%),radial-gradient(circle_at_bottom_right,_rgba(248,113,113,0.10),_transparent_24%),linear-gradient(180deg,_#f8fbff_0%,_#f8fafc_100%)] flex flex-col">
    <main class="flex-1 p-4 md:p-6 lg:p-8">
        <div class="w-full mx-auto px-2 md:px-4 lg:px-6">
            <div class="flex flex-col xl:flex-row justify-between items-start xl:items-end mb-12 gap-8">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-sm mb-4">
                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                        <h3 class="text-[11px] font-black text-primary uppercase tracking-[0.28em]">Your Journey</h3>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-950">Ride History</h2>
                    <p class="text-slate-500 font-medium mt-4 max-w-2xl">Track completed rides, revisit routes, and quickly book the same journey again from a cleaner trip archive.</p>
                </div>

                <div class="relative" id="filterDropdownContainer">
                    <div onclick="toggleFilter()" class="flex items-center gap-4 bg-white/90 backdrop-blur px-5 py-3 rounded-2xl shadow-[0_10px_30px_-18px_rgba(15,23,42,0.25)] border border-slate-200 hover:border-blue-300 transition-all duration-300 cursor-pointer group">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-blue-600 transition-colors">
                            <i class="fas fa-sliders text-sm"></i>
                        </div>
                        <div class="min-w-[120px]">
                            <span class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.22em]">Entries</span>
                            <span id="currentLimitText" class="block text-sm font-black text-slate-900 mt-1">5 Rides</span>
                        </div>
                        <i class="fas fa-chevron-down text-[10px] text-slate-300 group-hover:text-blue-500 transition-transform duration-300" id="filterChevron"></i>
                    </div>
                    
                    <div class="absolute top-full right-0 mt-3 w-44 bg-white rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.12)] border border-slate-100 overflow-hidden z-[100] opacity-0 invisible translate-y-[-5px] transition-all duration-300" id="filterPanel">
                        <div onclick="selectLimit(5, '5 Rides')" class="px-5 py-3.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer transition-colors border-b border-slate-50">5 Rides</div>
                        <div onclick="selectLimit(10, '10 Rides')" class="px-5 py-3.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer transition-colors border-b border-slate-50">10 Rides</div>
                        <div onclick="selectLimit(20, '20 Rides')" class="px-5 py-3.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer transition-colors border-b border-slate-50">20 Rides</div>
                        <div onclick="selectLimit(0, 'All Rides')" class="px-5 py-3.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer transition-colors">All Rides</div>
                    </div>
                </div>
            </div>

            <div id="fullHistoryContainer" class="w-full animate-in fade-in slide-in-from-bottom-4 duration-700">
                <div class="flex flex-col items-center justify-center py-40">
                    <div class="w-16 h-16 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                    <p class="mt-6 text-gray-400 font-bold uppercase tracking-widest text-xs">Fetching your rides...</p>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    #filterPanel.open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .limit-btn { color: #9CA3AF; }
    .limit-btn:hover { color: #000; background: #F3F4F6; }
    .limit-btn.active { background: #000 !important; color: #fff !important; }
</style>

<script>
    let currentLimit = 0;

    // History rendering
    async function fetchFullHistory(limit = 0) {
        currentLimit = limit;
        const container = document.getElementById('fullHistoryContainer');

        try {
            const response = await fetch(`api/get_rides.php?limit=${limit}`);
            const data = await response.json();

            if (data.success) {
                if (data.rides.length === 0) {
                    container.innerHTML = `
                        <div class="w-full text-center py-40">
                            <div class="w-20 h-20 bg-white rounded-[2rem] shadow-sm border border-slate-200 flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-car-side text-3xl text-slate-300"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800">No rides yet</h3>
                            <p class="text-slate-400 mt-2">When you take a ride, it will appear here.</p>
                            <a href="ride.php" class="inline-block mt-8 bg-slate-950 text-white px-8 py-4 rounded-2xl font-bold transition transform active:scale-95 shadow-xl shadow-slate-900/10 hover:bg-blue-700">Book a Ride</a>
                        </div>
                    `;
                    return;
                }

                const tableRows = data.rides.map(ride => {
                    const date = new Date(ride.created_at);
                    const formattedDate = date.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
                    const formattedTime = date.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    
                    let driverInfo = '';
                    if (ride.status === 'pending') {
                        driverInfo = `<span class="inline-flex items-center gap-2 bg-amber-50 text-amber-600 px-4 py-2 rounded-full text-[10px] font-extrabold uppercase tracking-[0.18em] border border-amber-100 shadow-sm"><span class="w-2 h-2 rounded-full bg-amber-400"></span>Pending Approval</span>`;
                    } else {
                        driverInfo = `
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 text-xs shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05)] border border-slate-100">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="flex flex-col mt-0.5">
                                            <span class="text-[14px] font-extrabold text-slate-900 leading-none">${ride.driver_name || 'Assigned Driver'}</span>
                                            <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mt-1.5 leading-none">${ride.cab_model || 'Vehicle'} • ${ride.cab_number || '---'}</span>
                                        </div>
                                    </div>`;
                    }
                    
                    return `
                    <tr class="hover:bg-slate-50/50 transition-colors duration-300 group">
                        <td class="py-5 px-6 align-middle border-b border-slate-100/60 last:border-0">
                            <div class="flex flex-col mt-1">
                                <span class="text-[13px] font-extrabold text-slate-800 leading-none">${formattedDate}</span>
                                <span class="text-[10px] font-bold text-slate-400 mt-2 leading-none">${formattedTime}</span>
                            </div>
                        </td>
                        <td class="py-5 px-6 align-middle border-b border-slate-100/60 last:border-0">
                            <div class="flex flex-col gap-2.5 mt-1">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0 shadow-sm"></div>
                                    <span class="text-[13px] font-bold text-slate-700 truncate" title="${ride.pickup_location}">${ride.pickup_location}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-primary flex-shrink-0 shadow-sm"></div>
                                    <span class="text-[13px] font-bold text-slate-500 truncate" title="${ride.destination}">${ride.destination}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6 align-middle border-b border-slate-100/60 last:border-0">
                            ${driverInfo}
                        </td>
                        <td class="py-5 px-6 align-middle border-b border-slate-100/60 last:border-0">
                            <div class="flex flex-col mt-1">
                                <span class="text-[13px] font-extrabold text-primary leading-none">₹${ride.fare}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2 leading-none">${ride.distance || '---'}</span>
                            </div>
                        </td>
                        <td class="py-5 px-6 align-middle border-b border-slate-100/60 last:border-0 text-center">
                            <div class="flex items-center justify-center transition-opacity duration-300">
                                <button onclick="seeRoute('${ride.pickup_location}', '${ride.destination}')" class="inline-flex items-center gap-2 h-10 px-4 bg-slate-900 text-white rounded-xl hover:bg-blue-700 transition-all flex items-center justify-center shadow-sm text-[11px] font-black uppercase tracking-[0.18em]" title="See Route">
                                    <i class="fas fa-map-marked-alt text-[11px]"></i> Route
                                </button>
                            </div>
                        </td>
                    </tr>
                    `;
                }).join('');

                container.innerHTML = `
                    <div class="bg-white/90 backdrop-blur rounded-[2rem] shadow-[0_16px_50px_-28px_rgba(15,23,42,0.18)] border border-white p-2">
                        <div class="overflow-x-auto w-full rounded-[1.8rem]">
                            <table class="w-full text-left border-collapse whitespace-nowrap">
                                <thead>
                                    <tr>
                                        <th class="py-5 px-6 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100/80">Date & Time</th>
                                        <th class="py-5 px-6 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100/80">Route Details</th>
                                        <th class="py-5 px-6 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100/80">Driver Info</th>
                                        <th class="py-5 px-6 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100/80">Fare & Dist.</th>
                                        <th class="py-5 px-6 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100/80 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${tableRows}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            }
        } catch (error) {
            console.error('History Error:', error);
            container.innerHTML = '<p class="col-span-full text-center text-red-500 font-bold uppercase tracking-widest text-xs">Failed to load history</p>';
        }
    }

    function changeLimit(limit) {
        fetchFullHistory(limit);
    }

    // Filter dropdown
    function toggleFilter() {
        const panel = document.getElementById('filterPanel');
        const chevron = document.getElementById('filterChevron');
        panel.classList.toggle('open');
        chevron.style.transform = panel.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0deg)';
    }

    function selectLimit(val, text) {
        document.getElementById('currentLimitText').innerText = text;
        fetchFullHistory(val);
        toggleFilter();
    }

    window.addEventListener('click', (e) => {
        const container = document.getElementById('filterDropdownContainer');
        if (container && !container.contains(e.target)) {
            document.getElementById('filterPanel').classList.remove('open');
            document.getElementById('filterChevron').style.transform = 'rotate(0deg)';
        }
    });

    // Route replay link
    function seeRoute(pickup, dest) {
        window.location.href = `ride.php?pickup=${encodeURIComponent(pickup)}&dest=${encodeURIComponent(dest)}&visualize=true`;
    }

    window.addEventListener('DOMContentLoaded', () => {
        fetchFullHistory(5);
    });
</script>

<?php include 'layout/footer.php'; ?>
