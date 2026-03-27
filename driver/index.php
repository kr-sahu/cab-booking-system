<?php
include 'inc/header.php';
include 'inc/sidebar.php';
?>

    <!-- Driver dashboard -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <div class="px-8 py-6 bg-white border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-gray-800">Dashboard</h2>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Welcome back, <?= htmlspecialchars($driver['name']) ?></p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Today is</p>
                    <p class="text-xs font-bold text-gray-700"><?= date('F d, Y') ?></p>
                </div>
                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 border border-gray-100"><i class="fas fa-calendar-alt text-sm"></i></div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 lg:p-10 bg-[#F8FAFC]">
            
            <div class="max-w-[1400px] mx-auto space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-5">
                        <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-xl shadow-inner"><i class="fas fa-wallet"></i></div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Earnings</p>
                            <h3 class="text-2xl font-black text-gray-800">₹<?= number_format($earnings, 2) ?></h3>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-5">
                        <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-xl shadow-inner"><i class="fas fa-route"></i></div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Completed Rides</p>
                            <h3 class="text-2xl font-black text-gray-800"><?= $rideCount ?></h3>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-5 relative group transition-all hover:border-primary/20">
                        <div id="statusIconBg" class="w-14 h-14 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center text-xl shadow-inner transition-colors duration-500"><i class="fas fa-signal"></i></div>
                        <div class="flex-1">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Current Status</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span id="statusLabel" class="text-[15px] font-black uppercase text-gray-400">Offline</span>
                                <div id="statusPulse" class="w-2 h-2 rounded-full bg-gray-300"></div>
                            </div>
                        </div>
                        <button onclick="toggleStatus()" title="Change Status" class="w-10 h-10 bg-gray-50 text-gray-300 rounded-lg flex items-center justify-center hover:bg-black hover:text-white transition-all"><i class="fas fa-power-off text-xs"></i></button>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-5">
                        <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-xl shadow-inner"><i class="fas fa-star"></i></div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Overall Rating</p>
                            <h3 class="text-2xl font-black text-gray-800">4.9</h3>
                        </div>
                    </div>
                </div>

                <div class="grid lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-2 space-y-6">
                        <div class="flex items-center justify-between px-4">
                            <h3 class="text-[13px] font-black text-gray-800 uppercase tracking-widest flex items-center gap-3">
                                <i class="fas fa-bolt text-primary"></i> Live Ride Management
                            </h3>
                            <div id="monitoringDot" class="flex items-center gap-2 opacity-0 transition-opacity">
                                <div class="w-2 h-2 bg-success rounded-full animate-pulse"></div>
                                <span class="text-[9px] font-black text-success uppercase tracking-widest">Live Monitoring</span>
                            </div>
                        </div>

                        <div id="rideContainer" class="min-h-[400px]">
                            <div class="bg-white rounded-[3rem] p-16 border border-gray-100 shadow-sm flex flex-col items-center justify-center text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-100 text-3xl mb-8 border border-gray-50 shadow-inner"><i class="fas fa-radar"></i></div>
                                <h4 class="text-2xl font-black text-gray-800">No Active Data</h4>
                                <p class="text-gray-400 max-w-[280px] text-sm mt-3 font-medium">Go Online to start receiving ride requests from nearby clients.</p>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1 space-y-6">
                        <h3 class="text-[13px] font-black text-gray-800 uppercase tracking-widest flex items-center gap-3 px-4">
                            <i class="fas fa-clock-rotate-left"></i> Recent Activity
                        </h3>

                        <div id="recentTripsContainer" class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-4 h-full min-h-[500px]">
                            <div class="text-center py-20 px-8">
                                <i class="fas fa-history text-3xl text-gray-100 mb-6"></i>
                                <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">No Recent Activity</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div id="map" class="hidden h-0 w-0 opacity-0 pointer-events-none"></div>

    </main>

    <script>
        let map, routingControl;
        let checkInterval;

        // Mirror the shared status state into dashboard-specific UI.
        const originalUpdateStatusUI = updateStatusUI;
        updateStatusUI = function() {
            if (typeof originalUpdateStatusUI === 'function') originalUpdateStatusUI();
            
            const label = document.getElementById('statusLabel');
            const pulse = document.getElementById('statusPulse');
            const iconBg = document.getElementById('statusIconBg');
            const monDot = document.getElementById('monitoringDot');
            
            if(!label || !pulse || !iconBg) return;

            if(driverStatus === 'available') {
                label.innerText = "Online";
                label.className = "text-[15px] font-black uppercase text-success";
                pulse.className = "w-2 h-2 rounded-full bg-success pulse-online";
                iconBg.className = "w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-xl shadow-inner transition-colors duration-500";
                if(monDot) monDot.style.opacity = "1";
            } else if(driverStatus === 'on_trip') {
                label.innerText = "On Trip";
                label.className = "text-[15px] font-black uppercase text-orange-500";
                pulse.className = "w-2 h-2 rounded-full bg-orange-500";
                iconBg.className = "w-14 h-14 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-xl shadow-inner transition-colors duration-500";
                if(monDot) monDot.style.opacity = "1";
            } else {
                label.innerText = "Offline";
                label.className = "text-[15px] font-black uppercase text-gray-400";
                pulse.className = "w-2 h-2 rounded-full bg-gray-300";
                iconBg.className = "w-14 h-14 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center text-xl shadow-inner transition-colors duration-500";
                if(monDot) monDot.style.opacity = "0";
            }
        }

        async function fetchOrders() {
            if(driverStatus === 'offline') {
                stopMonitoring();
                return;
            };
            
            const res = await fetch('api/get_bookings.php');
            const data = await res.json();
            
            if(data.success) {
                renderRecentTrips(data.recent_trips);
                if(data.active_trip) {
                    driverStatus = 'on_trip';
                    updateStatusUI();
                    renderActiveTrip(data.active_trip);
                    setTimeout(() => updateMapRoute(data.active_trip), 100);
                } else {
                    renderOrders(data.bookings);
                }
            }
        }

        function renderRecentTrips(trips) {
            const container = document.getElementById('recentTripsContainer');
            if(!trips || trips.length === 0) return;

            container.innerHTML = `
                <div class="space-y-4">
                    ${trips.map(t => `
                        <div class="p-5 rounded-[1.5rem] border border-gray-50 bg-gray-50/30 hover:bg-white hover:shadow-sm transition-all group">
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest leading-none">${new Date(t.created_at).toLocaleDateString('en-US', {month:'short', day:'numeric'})}</span>
                                <span class="text-[11px] font-black text-emerald-500 leading-none">₹${parseFloat(t.fare).toFixed(2)}</span>
                            </div>
                            <p class="text-[13px] font-extrabold text-gray-800 leading-tight group-hover:text-black">${t.passenger_name}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <div class="w-1 h-1 rounded-full bg-primary/30"></div>
                                <p class="text-[10px] font-bold text-gray-400 truncate">${t.destination}</p>
                            </div>
                        </div>
                    `).join('')}
                    <a href="trips.php" class="block text-center py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-black transition-colors">View All History</a>
                </div>
            `;
        }

        function renderOrders(orders) {
            const container = document.getElementById('rideContainer');
            if(!orders || orders.length === 0) {
                container.innerHTML = `
                    <div class="bg-white rounded-[3rem] p-16 border border-gray-100 shadow-sm flex flex-col items-center justify-center text-center animate-in fade-in duration-500">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-100 text-3xl mb-8 border border-gray-50 shadow-inner"><i class="fas fa-radar fa-pulse"></i></div>
                        <h4 class="text-2xl font-black text-gray-800">Searching for Rides...</h4>
                        <p class="text-gray-400 max-w-[280px] text-sm mt-3 font-medium">Sit tight! We're looking for passengers in your area.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = orders.map(order => `
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 space-y-6 animate-in fade-in slide-in-from-bottom-4 transition-transform hover:scale-[1.01]">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="bg-primary/10 text-primary text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">New Ride Request</span>
                            <h4 class="font-black text-4xl mt-4 text-slate-900">₹${parseFloat(order.fare).toFixed(2)}</h4>
                        </div>
                        <div class="text-right">
                             <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Passenger</p>
                             <p class="font-extrabold text-lg text-gray-800 mt-1">${order.passenger_name}</p>
                        </div>
                    </div>

                    <div class="space-y-5 p-6 bg-slate-50/50 rounded-3xl border border-slate-100">
                        <div class="flex items-start gap-4">
                            <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">From</p>
                                <p class="font-bold text-sm text-slate-700 leading-tight">${order.pickup_location}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-2 h-2 rounded-full bg-primary mt-1.5 flex-shrink-0"></div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">To</p>
                                <p class="font-bold text-sm text-slate-700 leading-tight">${order.destination}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <button onclick="acceptRide(${order.id})" class="bg-blue-600 text-white py-4 rounded-2xl font-black text-[12px] uppercase tracking-widest shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition transform active:scale-95 flex items-center justify-center gap-3">
                            <i class="fas fa-check-circle"></i> Accept Ride
                        </button>
                        <button class="bg-slate-100 text-slate-400 py-4 rounded-2xl font-black text-[12px] uppercase tracking-widest hover:bg-slate-200 hover:text-slate-600 transition">Discard</button>
                    </div>
                </div>
            `).join('');
        }

        function renderActiveTrip(trip) {
            const container = document.getElementById('rideContainer');
            container.innerHTML = `
                <div class="bg-white rounded-[3rem] border border-gray-100 shadow-xl overflow-hidden animate-in zoom-in-95 duration-500">
                    <div class="p-8 lg:p-12">
                        <div class="flex justify-between items-center mb-10">
                            <div>
                                <span class="bg-success/10 text-success text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-success/20">Active Booking</span>
                                <h4 class="font-black text-4xl mt-6 text-gray-800 tracking-tight">${trip.passenger_name}</h4>
                            </div>
                            <div class="flex gap-4">
                                <a href="tel:${trip.passenger_phone}" class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl hover:bg-blue-100 transition shadow-sm border border-blue-100">
                                    <i class="fas fa-phone-alt"></i>
                                </a>
                                <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center text-xl border border-slate-100 shadow-inner">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                            </div>
                        </div>

                        <!-- TRIP DETAIL CARDS -->
                        <div class="grid md:grid-cols-2 gap-6 mb-10">
                            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 space-y-5">
                                <div class="flex items-start gap-4">
                                    <div class="w-2 h-2 rounded-full bg-blue-400 mt-1.5"></div>
                                    <div><p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pickup Address</p><p class="text-[13px] font-bold text-slate-700 mt-1">${trip.pickup_location}</p></div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-2 h-2 rounded-full bg-primary mt-1.5"></div>
                                    <div><p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Drop Location</p><p class="text-[13px] font-bold text-slate-700 mt-1">${trip.destination}</p></div>
                                </div>
                            </div>
                            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 flex flex-col justify-center gap-6">
                                <div class="flex justify-between items-center">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Fare</p>
                                    <p class="text-3xl font-black text-slate-900 leading-none">₹${parseFloat(trip.fare).toFixed(2)}</p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Distance</p>
                                    <p class="text-lg font-black text-slate-600 leading-none">${trip.distance || '---'}</p>
                                </div>
                            </div>
                        </div>

                        <!-- MINI MAP CONTAINER -->
                        <div class="w-full h-[300px] bg-slate-100 rounded-[2rem] border border-slate-200 mb-10 overflow-hidden relative shadow-inner">
                            <div id="activeMap" class="w-full h-full z-10"></div>
                            <div class="absolute top-6 right-6 z-20 bg-white/95 px-4 py-2 rounded-xl shadow-md border border-slate-100">
                                 <p id="miniRouteMeta" class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Calculating Route...</p>
                            </div>
                        </div>

                        <button onclick="completeRide(${trip.id})" class="w-full h-[70px] bg-emerald-600 text-white rounded-2xl font-black text-[14px] uppercase tracking-widest shadow-2xl shadow-emerald-500/30 hover:bg-emerald-700 transition-all transform active:scale-[0.98] flex items-center justify-center gap-4">
                            <i class="fas fa-flag-checkered"></i> Mark Trip as Completed
                        </button>
                    </div>
                </div>
            `;
        }

        async function acceptRide(id) {
            const btn = event.currentTarget;
            btn.disabled = true; btn.innerText = "Processing...";
            try {
                const res = await fetch('api/accept.php', { method: 'POST', body: JSON.stringify({ booking_id: id }) });
                const data = await res.json();
                if(data.success) fetchOrders();
                else alert(data.message);
            } catch(e) { console.error(e); }
        }

        async function completeRide(id) {
            if(!confirm("Are you sure the passenger has safely reached the destination?")) return;
            try {
                const res = await fetch('api/complete.php', { method: 'POST', body: JSON.stringify({ booking_id: id }) });
                const data = await res.json();
                if(data.success) {
                    driverStatus = 'available';
                    updateStatusUI();
                    if(routingControl) map.removeControl(routingControl);
                    fetchOrders();
                }
            } catch(e) { console.error(e); }
        }

        async function geocodeTripLocation(query) {
            const q = String(query || '').trim();
            if (!q) return null;

            const variants = [q, `${q}, India`];

            for (const variant of variants) {
                try {
                    const params = new URLSearchParams({
                        q: variant,
                        format: 'jsonv2',
                        addressdetails: '1',
                        limit: '1',
                        countrycodes: 'in',
                        bounded: '1',
                        viewbox: '68,38,98,6'
                    });

                    const response = await fetch(`https://nominatim.openstreetmap.org/search?${params.toString()}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await response.json();

                    if (Array.isArray(data) && data[0]) {
                        return [Number(data[0].lat), Number(data[0].lon)];
                    }
                } catch (error) {
                    console.error('Trip geocode failed:', error);
                }
            }

            return null;
        }

        async function updateMapRoute(trip) {
            const mapEl = document.getElementById('activeMap');
            if(!mapEl) return;
            
            if (map) map.remove();
            map = L.map('activeMap', { zoomControl: false }).setView([20.5937, 78.9629], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const meta = document.getElementById('miniRouteMeta');
            if(meta) meta.innerText = 'Resolving Route...';

            const start = await geocodeTripLocation(trip.pickup_location);
            const end = await geocodeTripLocation(trip.destination);

            if (!start || !end) {
                if(meta) meta.innerText = 'Route unavailable';
                return;
            }
            routingControl = L.Routing.control({
                waypoints: [L.latLng(start[0], start[1]), L.latLng(end[0], end[1])],
                routeWhileDragging: false, show: false,
                createMarker: (i, wp) => L.marker(wp.latLng, {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: `<div class="w-4 h-4 rounded-full border-4 border-white ${i === 0 ? 'bg-black' : 'bg-primary'} shadow-lg"></div>`,
                        iconSize: [16, 16]
                    })
                })
            }).on('routesfound', (e) => {
                const s = e.routes[0].summary;
                if(meta) meta.innerText = `${(s.totalDistance / 1000).toFixed(1)} km • ${Math.round(s.totalTime / 60)} mins`;
            }).on('routingerror', () => {
                if(meta) meta.innerText = 'Route unavailable';
            }).addTo(map);

            map.fitBounds(L.latLngBounds([start, end]), { padding: [50, 50] });
        }

        function startMonitoring() {
            if(checkInterval) clearInterval(checkInterval);
            fetchOrders();
            checkInterval = setInterval(fetchOrders, 4000);
        }

        function stopMonitoring() {
            if(checkInterval) clearInterval(checkInterval);
            const container = document.getElementById('rideContainer');
            container.innerHTML = `
                <div class="bg-white rounded-[3rem] p-16 border border-gray-100 shadow-sm flex flex-col items-center justify-center text-center animate-in fade-in duration-500">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 text-3xl mb-8 border border-gray-50 shadow-inner"><i class="fas fa-moon"></i></div>
                    <h4 class="text-2xl font-black text-gray-400">Panel is Offline</h4>
                    <p class="text-gray-300 max-w-[280px] text-sm mt-3 font-medium">Toggle your status to Online to start accepting new trip requests.</p>
                </div>
            `;
        }

        window.onload = () => {
            updateStatusUI();
            if(driverStatus !== 'offline') startMonitoring();
            else stopMonitoring();
        };
    </script>
</body>
</html>
