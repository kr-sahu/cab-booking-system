<?php 
include 'layout/header.php'; 
?>

    <!-- Ride workspace -->
    <main class="py-16 mx-auto px-6 md:px-12 bg-slate-100 min-h-[85vh] relative flex flex-col justify-center">
        <div class="max-w-[1400px] w-full mx-auto relative flex-1 flex flex-col pt-4">
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-6">
                <div>
                    <div class="inline-block px-4 py-1.5 bg-primary/10 border border-primary/20 rounded-full mb-3">
                        <span id="pageEyebrow" class="text-[12px] font-bold text-primary uppercase tracking-widest">Request a Ride</span>
                    </div>
                    <h2 id="pageHeading" class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight">Where to?</h2>
                    <p id="pageSubheading" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-2 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                        Use Zuber Assistant below to book your ride
                    </p>
                </div>
                <div class="flex gap-4">
                    <div class="bg-white p-4 rounded-[1.25rem] shadow-sm border border-slate-200 flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-xl shadow-inner border border-emerald-100/50 flex items-center justify-center"><i class="fas fa-shield-alt"></i></div>
                        <div><p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Secure</p><p class="text-[13px] font-bold text-slate-800 tracking-wide mt-0.5">Verified Service</p></div>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6 flex-1 min-h-[600px]">
                
                <div class="lg:col-span-2 relative bg-white rounded-[2rem] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden border border-slate-200 min-h-[600px] flex flex-col">
                    <?php if(!$isLoggedIn): ?>
                        <div class="absolute inset-0 z-[2000] bg-white/60 backdrop-blur-md flex flex-col items-center justify-center p-8 text-center rounded-[2rem]">
                            <div class="w-16 h-16 bg-slate-800 text-white rounded-2xl flex items-center justify-center mb-6 shadow-md border border-slate-700">
                                <i class="fas fa-lock text-2xl"></i>
                            </div>
                            <h4 class="text-2xl font-extrabold text-slate-900 mb-2">Login Required</h4>
                            <p class="text-slate-500 max-w-xs mb-8 text-sm font-medium">Login to your account to access the map and request a ride.</p>
                            <a href="auth.php?mode=login" class="bg-slate-800 text-white px-8 py-3.5 rounded-xl font-bold shadow-md hover:bg-slate-900 transition-colors tracking-wide text-[15px]">Log In Now</a>
                        </div>
                    <?php endif; ?>

                    <div id="map" class="w-full h-full flex-1 z-0"></div>

                    <div class="absolute top-6 right-6 bg-white/95 backdrop-blur-md px-4 py-2.5 rounded-xl shadow-sm border border-slate-200 z-[1000] pointer-events-none fade-in">
                        <p class="text-[11px] font-bold text-slate-600 flex items-center gap-2"><i class="fas fa-location-dot text-primary"></i> Select your pickup & drop location</p>
                    </div>

                    <div id="routeDetails" class="hidden absolute top-6 left-6 w-[320px] bg-white rounded-2xl shadow-xl z-[1000] overflow-hidden flex flex-col max-h-[500px] animate-in slide-in-from-left-4 fade-in duration-500 border border-slate-100"></div>
                </div>

                <div class="lg:col-span-1 flex flex-col relative h-[600px]">
                    
                    <div id="panelPlaceholder" class="absolute inset-0 bg-white rounded-[2rem] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-200 p-8 flex flex-col items-center justify-center text-center transition-all duration-300 z-10">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-3xl mb-5 shadow-inner border border-slate-100">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h4 id="placeholderTitle" class="text-[22px] font-extrabold text-slate-800 mb-2">Ready to Book?</h4>
                        <p id="placeholderText" class="text-slate-500 text-[13px] font-medium mb-6 leading-relaxed max-w-[250px]">Open the assistant to start planning your route and get fare estimates.</p>
                        
                        <div class="w-full flex flex-col gap-3 text-left bg-slate-50 p-4 rounded-xl border border-slate-100 mb-6">
                            <div class="flex items-center gap-3">
                                <div class="text-primary w-5 text-center"><i class="fas fa-bolt text-sm"></i></div>
                                <p class="text-[12px] font-bold text-slate-700">Quick fare estimates</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-emerald-500 w-5 text-center"><i class="fas fa-shield-alt text-sm"></i></div>
                                <p class="text-[12px] font-bold text-slate-700">Secure driver booking</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-blue-500 w-5 text-center"><i class="fas fa-route text-sm"></i></div>
                                <p class="text-[12px] font-bold text-slate-700">Live map routing</p>
                            </div>
                        </div>

                        <button id="openAssistantBtn" onclick="toggleChat()" class="w-full bg-primary text-white py-3.5 rounded-xl font-bold text-[14px] hover:bg-red-600 transition-colors shadow-md flex items-center justify-center gap-2">
                            <i class="fas fa-comment-dots text-white"></i> Open Assistant
                        </button>
                    </div>

                    <div id="chatInterface" class="absolute inset-0 bg-white rounded-[2rem] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-200 overflow-hidden flex flex-col transition-all duration-300 z-0 opacity-0 pointer-events-none translate-x-4">
                        <div class="bg-white p-5 text-slate-800 flex justify-between items-center border-b border-slate-100 relative z-10 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)] animate-pulse"></div>
                                <h3 class="font-bold text-[14px] tracking-wide">Zuber Assistant</h3>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="toggleNotificationInbox()" class="relative text-slate-400 hover:text-slate-700 transition-colors w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100" title="Booking Notifications">
                                    <i class="fas fa-bell text-[13px]"></i>
                                    <span id="notificationBadge" class="hidden absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 shadow-[0_0_0_2px_white]"></span>
                                </button>
                                <button onclick="toggleChat()" class="text-slate-400 hover:text-slate-700 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div id="assistantChatView" class="flex-1 flex flex-col min-h-0">
                            <div id="chatBox" class="flex-1 p-3 overflow-y-auto bg-slate-50 flex flex-col gap-1.5 no-scrollbar">
                                <div class="flex justify-start animate-in fade-in slide-in-from-bottom-2 duration-300">
                                    <div id="assistantWelcomeMessage" class="bg-white border border-slate-200 text-slate-800 px-3 py-2 inline-block max-w-[75%] rounded-[1rem] rounded-tl-sm text-[12px] font-medium leading-relaxed">Hi! I'm your Zuber assistant. Where should I pick you up from?</div>
                                </div>
                            </div>
                            <div class="p-3 bg-white border-t border-slate-100 flex flex-col gap-2">
                                <div class="flex gap-2">
                                    <input type="date" id="pickupDate" min="<?php echo date('Y-m-d'); ?>" required class="flex-1 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[12px] outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium text-slate-600">
                                    <div class="relative w-28">
                                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[12px]">
                                            <i class="far fa-clock"></i>
                                        </span>
                                        <input type="text" id="pickupTime" placeholder="00:00" inputmode="numeric" maxlength="5" required class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[12px] outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium text-slate-600 placeholder-slate-400">
                                    </div>
                                </div>
                                <div class="flex gap-2 relative">
                                    <input type="text" id="chatInput" placeholder="Enter location..." class="flex-1 pl-4 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[13px] outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all font-medium text-slate-800 placeholder-slate-400" onkeydown="if(event.key === 'Enter') sendMessage()">
                                    <button onclick="sendMessage()" class="bg-slate-50 border border-slate-200 text-primary w-10 h-10 rounded-lg hover:bg-slate-100 transition-colors flex items-center justify-center shadow-sm shrink-0"><i class="fas fa-paper-plane text-[12px]"></i></button>
                                </div>
                            </div>
                        </div>
                        <div id="assistantNotificationView" class="hidden flex-1 flex flex-col min-h-0 bg-slate-50">
                            <div class="px-4 py-3 border-b border-slate-100 bg-white">
                                <p class="text-[10px] font-black text-primary uppercase tracking-[0.22em]">Notifications</p>
                                <p class="text-[12px] font-bold text-slate-500 mt-1">Booking approvals and assigned driver updates appear here.</p>
                            </div>
                            <div id="notificationBox" class="flex-1 overflow-y-auto p-3 flex flex-col gap-2 no-scrollbar">
                                <div class="bg-white border border-slate-200 text-slate-700 px-4 py-3 rounded-[1rem] text-[12px] font-medium leading-relaxed">
                                    Open the bell anytime to see booking approval updates.
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-white border-t border-slate-100">
                                <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3 text-[12px] font-bold text-slate-400">
                                    Notifications are read-only here.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </main>

    <!-- Payment modal -->
    <div id="paymentModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[10000] flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-[450px] p-8 rounded-[2rem] shadow-2xl border border-slate-100 chat-slide-up relative">
            <button onclick="closePayment()" class="absolute top-6 right-6 text-slate-400 hover:bg-slate-100 rounded-lg w-8 h-8 flex items-center justify-center transition-colors"><i class="fas fa-times"></i></button>
            <div class="mb-8">
                <div class="inline-block px-3 py-1 bg-primary/10 border border-primary/20 rounded-md mb-3">
                    <span class="text-[10px] font-bold text-primary uppercase tracking-widest">Checkout</span>
                </div>
                <h2 class="text-3xl font-extrabold text-slate-900 leading-tight">Review Order</h2>
                <p class="text-slate-500 mt-1 text-sm font-medium">Verify your ride before confirming</p>
            </div>
            <div id="paymentDetails" class="space-y-3 mb-8">
                <div id="payCashBtn" onclick="selectPayment('cash')" class="bg-white border-2 border-primary shadow-sm p-4 rounded-xl flex items-center justify-between cursor-pointer transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-slate-50 text-slate-600 rounded-lg border border-slate-100 flex items-center justify-center shadow-inner"><i class="fas fa-money-bill-wave"></i></div>
                        <div><p class="font-extrabold text-[13px] text-slate-800">Cash Payment</p><p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Pay directly to driver</p></div>
                    </div>
                    <div id="cashCheck" class="text-primary text-lg"><i class="fas fa-check-circle"></i></div>
                </div>

                <div id="payCardBtn" onclick="selectPayment('card')" class="bg-white border border-slate-200 shadow-sm p-4 rounded-xl flex items-center justify-between cursor-pointer hover:border-slate-300 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-slate-50 text-slate-600 rounded-lg border border-slate-100 flex items-center justify-center shadow-inner"><i class="fas fa-credit-card"></i></div>
                        <div><p class="font-extrabold text-[13px] text-slate-800">Card Payment</p><p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Credit or Debit</p></div>
                    </div>
                    <div id="cardCheck" class="text-slate-200 text-lg"><i class="far fa-circle"></i></div>
                </div>

                <div id="cardFields" class="hidden mt-2 p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3 animate-in fade-in slide-in-from-top-2 duration-300">
                    <input type="text" id="cardNumber" placeholder="Card Number (0000 0000 0000)" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg text-[13px] outline-none focus:border-primary font-medium text-slate-700" maxlength="14">
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" id="cardExpiry" placeholder="MM/YY" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg text-[13px] outline-none focus:border-primary font-medium text-slate-700" maxlength="5">
                        <input type="text" id="cardCvv" placeholder="CVV" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg text-[13px] outline-none focus:border-primary font-medium text-slate-700" maxlength="3">
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-6">
                <div class="flex justify-between items-center mb-6 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <span class="text-slate-500 font-bold uppercase tracking-widest text-[11px]">Estimated Fare</span>
                    <span id="finalPrice" class="text-2xl font-black text-slate-900">₹0.00</span>
                </div>
                <button onclick="confirmBooking()" class="w-full bg-slate-800 text-white py-4 rounded-xl font-bold text-[15px] shadow-md hover:bg-slate-900 transition-colors">Book Now</button>
            </div>
        </div>
    </div>

    <!-- Assigned driver modal -->
    <div id="driverModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[10001] flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-[420px] p-0 rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden chat-slide-up relative">
            <div class="bg-slate-800 p-8 text-white relative">
                <button onclick="document.getElementById('driverModal').classList.add('hidden')" class="absolute top-6 right-6 text-slate-400 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-700"><i class="fas fa-times"></i></button>
                <div class="inline-block px-3 py-1 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-md mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest">Driver Assigned</span>
                </div>
                <h2 class="text-3xl font-extrabold leading-tight">Your Ride is Here</h2>
                <div class="mt-8 space-y-3">
                    <div class="flex items-center gap-4 bg-white/5 border border-white/10 p-3.5 rounded-xl">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center text-emerald-400 border border-white/5"><i class="fas fa-location-arrow text-sm"></i></div>
                        <div class="flex-1 min-w-0"><p class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Pickup</p><p id="assignedPickup" class="text-[13px] font-semibold truncate text-white mt-0.5">---</p></div>
                    </div>
                    <div class="flex items-center gap-4 bg-white/5 border border-white/10 p-3.5 rounded-xl">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center text-primary border border-white/5"><i class="fas fa-map-marker-alt text-sm"></i></div>
                        <div class="flex-1 min-w-0"><p class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Destination</p><p id="assignedDest" class="text-[13px] font-semibold truncate text-white mt-0.5">---</p></div>
                    </div>
                </div>
                <div class="mt-8 flex items-center gap-5">
                    <div class="w-14 h-14 bg-slate-700 rounded-xl flex items-center justify-center text-xl border border-slate-600 shadow-inner"><i class="fas fa-user-tie text-slate-300"></i></div>
                    <div>
                        <p id="assignedDriverName" class="text-[17px] font-bold text-white">---</p>
                        <p id="assignedDriverContact" class="text-[13px] font-medium text-slate-400 mt-0.5 flex items-center gap-1.5"><i class="fas fa-phone-alt text-[10px]"></i> ---</p>
                    </div>
                </div>
            </div>
            <div class="p-8 space-y-6 bg-white">
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-lg shadow-sm border border-slate-100 flex items-center justify-center text-slate-400 text-lg"><i class="fas fa-taxi"></i></div>
                        <div>
                            <p id="assignedCabModel" class="font-extrabold text-slate-800 text-[14px]">---</p>
                            <p id="assignedCabNumber" class="text-[11px] text-primary font-bold uppercase tracking-widest mt-0.5">---</p>
                        </div>
                    </div>
                </div>
                <div class="bg-primary/5 border border-primary/20 p-5 rounded-xl text-center">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Pickup OTP</p>
                    <div class="flex justify-center gap-3"><span id="bookingOTP" class="text-3xl font-black text-primary tracking-[0.2em]">0000</span></div>
                    <p class="text-[10px] text-slate-400 font-medium mt-3 flex items-center justify-center gap-2"><i class="fas fa-info-circle"></i> Give this code to the driver at pickup</p>
                </div>
                <button onclick="document.getElementById('driverModal').classList.add('hidden')" class="w-full bg-slate-800 text-white py-4 rounded-xl font-bold text-[14px] hover:bg-slate-900 transition-colors shadow-md">Got it</button>
            </div>
        </div>
    </div>

    <!-- Client-Side State and Logic -->
    <script>
        // Auth state derived from PHP session
        const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

        // Leaflet Map Initialization (India focus)
        const map = L.map('map', { 
            zoomControl: false, 
            maxBounds: L.latLngBounds(L.latLng(6.0, 68.0), L.latLng(38.0, 98.0)), 
            maxBoundsViscosity: 1.0, 
            minZoom: 5 
        }).setView([20.5937, 78.9629], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        // Core variables for route management
        let routeControl = null, pickupCoords = null, dropCoords = null, stepMarker = null, fallbackRouteLine = null;
        let pickupLoc = "", dropLoc = "", chatStep = 0, isHistoryView = false, currentDistance = "", currentRoutePrice = "";
        let isFetching = false;
        let isNotificationView = false;

        /**
         * Clears chat history to ensure it starts fresh after refresh
         */
        function clearPersistedChat() {
            localStorage.removeItem('zuber_chat_history');
            localStorage.removeItem('zuber_route_details');
            localStorage.removeItem('zuber_chat_state');
        }

        function applyHistoryModeContent() {
            // Switches the page copy between normal booking mode and history replay mode.
            const pageEyebrow = document.getElementById('pageEyebrow');
            const pageHeading = document.getElementById('pageHeading');
            const pageSubheading = document.getElementById('pageSubheading');
            const placeholderTitle = document.getElementById('placeholderTitle');
            const placeholderText = document.getElementById('placeholderText');
            const openAssistantBtn = document.getElementById('openAssistantBtn');
            const assistantWelcomeMessage = document.getElementById('assistantWelcomeMessage');

            if (isHistoryView) {
                if (pageEyebrow) pageEyebrow.textContent = 'Trip Replay';
                if (pageHeading) pageHeading.textContent = 'Review This Route';
                if (pageSubheading) pageSubheading.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>Open Zuber Assistant to revisit this trip or book it again`;
                if (placeholderTitle) placeholderTitle.textContent = 'Review Past Journey';
                if (placeholderText) placeholderText.textContent = 'Open the assistant to replay this route, review the fare path, or book the same trip again.';
                if (openAssistantBtn) openAssistantBtn.innerHTML = `<i class="fas fa-route text-white"></i> Review Journey`;
                if (assistantWelcomeMessage) assistantWelcomeMessage.innerHTML = `You are viewing a past route. Review the trip, inspect the route, or choose a new pickup time to book it again.`;
                return;
            }

            if (pageEyebrow) pageEyebrow.textContent = 'Request a Ride';
            if (pageHeading) pageHeading.textContent = 'Where to?';
            if (pageSubheading) pageSubheading.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>Use Zuber Assistant below to book your ride`;
            if (placeholderTitle) placeholderTitle.textContent = 'Ready to Book?';
            if (placeholderText) placeholderText.textContent = 'Open the assistant to start planning your route and get fare estimates.';
            if (openAssistantBtn) openAssistantBtn.innerHTML = `<i class="fas fa-comment-dots text-white"></i> Open Assistant`;
            if (assistantWelcomeMessage) assistantWelcomeMessage.innerHTML = `Hi! I'm your Zuber assistant. Where should I pick you up from?`;
        }

        /**
         * Disables all previous action buttons
         */
        function disablePreviousActions() {
            const btns = document.querySelectorAll('#chatBox button');
            btns.forEach(btn => {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                btn.onclick = null;
            });
        }

        /**
         * Displays a typing indicator for the assistant
         */
        function showTyping() {
            const d = document.createElement('div');
            d.id = 'typing-indicator';
            d.className = 'flex justify-start animate-in fade-in slide-in-from-bottom-2 duration-300 mb-2';
            d.innerHTML = `
                <div class="bg-white border border-slate-200 text-slate-800 px-4 py-3 rounded-[1.25rem] rounded-tl-sm flex gap-1 shadow-sm">
                    <div class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce [animation-delay:-0.15s]"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce [animation-delay:-0.3s]"></div>
                </div>`;
            const box = document.getElementById('chatBox');
            box.appendChild(d);
            box.scrollTop = box.scrollHeight;
            return d;
        }

        /**
         * Removes typing indicator
         */
        function removeTyping(el) { if(el && el.parentNode) el.remove(); }

        function buildGeocodeQueries(q) {
            const raw = String(q || '').trim();
            const variants = new Set([raw, `${raw}, India`]);

            const replacements = [
                { pattern: /brahmapur/gi, replacement: 'Berhampur' },
                { pattern: /bhubanswar|bhubaneshwar|bhuvaneswar|bhubneswar|bhubaneshwer/gi, replacement: 'Bhubaneswar' },
                { pattern: /vishakapatnam|vizag/gi, replacement: 'Visakhapatnam' },
                { pattern: /calcutta/gi, replacement: 'Kolkata' },
                { pattern: /bombay/gi, replacement: 'Mumbai' },
                { pattern: /bangalore/gi, replacement: 'Bengaluru' },
                { pattern: /madras/gi, replacement: 'Chennai' }
            ];

            replacements.forEach(({ pattern, replacement }) => {
                if (pattern.test(raw)) {
                    const corrected = raw.replace(pattern, replacement);
                    variants.add(corrected);
                    variants.add(`${corrected}, India`);
                }
            });

            return Array.from(variants).filter(Boolean);
        }

        function normalizeLocationKey(value) {
            return String(value || '')
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, ' ')
                .trim();
        }

        function isWithinIndia(lat, lon) {
            return lat >= 6 && lat <= 38 && lon >= 68 && lon <= 98;
        }

        function formatPlaceDisplay(name, city, state, country) {
            const parts = [name, city, state, country]
                .map(part => String(part || '').trim())
                .filter(Boolean);

            const uniqueParts = [];
            parts.forEach(part => {
                if (!uniqueParts.includes(part)) uniqueParts.push(part);
            });

            return uniqueParts.join(', ');
        }

        function getLocalGeocodeResult(q) {
            // Provides fast built-in matches for common Indian locations and aliases.
            const normalized = normalizeLocationKey(q);
            if (!normalized) return null;

            const knownLocations = [
                {
                    keys: ['brahmapur', 'berhampur', 'brahmapur odisha', 'berhampur odisha'],
                    lat: 19.3149,
                    lon: 84.7941,
                    display: 'Brahmapur, Odisha'
                },
                {
                    keys: ['bhubaneswar', 'bhubanswar', 'bhubaneshwar', 'bhuvaneswar', 'bhubneswar'],
                    lat: 20.2961,
                    lon: 85.8245,
                    display: 'Bhubaneswar, Odisha'
                },
                {
                    keys: ['hinjilicut', 'hinjilicut odisha'],
                    lat: 19.4825,
                    lon: 84.7449,
                    display: 'Hinjilicut, Odisha'
                },
                {
                    keys: ['kolkata', 'calcutta'],
                    lat: 22.5726,
                    lon: 88.3639,
                    display: 'Kolkata, West Bengal'
                },
                {
                    keys: ['bhubaneswar municipal corporation'],
                    lat: 20.2961,
                    lon: 85.8245,
                    display: 'Bhubaneswar Municipal Corporation, Odisha'
                },
                {
                    keys: ['delhi', 'new delhi', 'delhi ncr'],
                    lat: 28.6139,
                    lon: 77.2090,
                    display: 'New Delhi, Delhi'
                },
                {
                    keys: ['mumbai', 'bombay'],
                    lat: 19.0760,
                    lon: 72.8777,
                    display: 'Mumbai, Maharashtra'
                },
                {
                    keys: ['chennai', 'madras'],
                    lat: 13.0827,
                    lon: 80.2707,
                    display: 'Chennai, Tamil Nadu'
                },
                {
                    keys: ['bengaluru', 'bangalore'],
                    lat: 12.9716,
                    lon: 77.5946,
                    display: 'Bengaluru, Karnataka'
                },
                {
                    keys: ['hyderabad'],
                    lat: 17.3850,
                    lon: 78.4867,
                    display: 'Hyderabad, Telangana'
                },
                {
                    keys: ['pune', 'poona'],
                    lat: 18.5204,
                    lon: 73.8567,
                    display: 'Pune, Maharashtra'
                }
            ];

            const exactMatch = knownLocations.find(location =>
                location.keys.some(key => normalized === key || normalized.includes(key))
            );

            return exactMatch || null;
        }

        function resolveCanonicalLocationInput(q) {
            const raw = String(q || '').trim();
            const normalized = normalizeLocationKey(raw);

            const aliasMap = [
                { match: ['bhubaneswar', 'bhubanswar', 'bhubaneshwar', 'bhuvaneswar', 'bhubneswar', 'bhubaneshwer'], canonical: 'Bhubaneswar' },
                { match: ['brahmapur', 'berhampur'], canonical: 'Brahmapur' },
                { match: ['hinjilicut'], canonical: 'Hinjilicut' },
                { match: ['delhi', 'new delhi', 'delhi ncr'], canonical: 'New Delhi' },
                { match: ['kolkata', 'calcutta'], canonical: 'Kolkata' },
                { match: ['visakhapatnam', 'vishakapatnam', 'vizag'], canonical: 'Visakhapatnam' }
            ];

            const hit = aliasMap.find(item => item.match.some(name => normalized === name || normalized.includes(name)));
            return hit ? hit.canonical : raw;
        }

        function parsePhotonFeature(feature) {
            if (!feature?.geometry?.coordinates) return null;

            const lon = Number(feature.geometry.coordinates[0]);
            const lat = Number(feature.geometry.coordinates[1]);
            if (!isWithinIndia(lat, lon)) return null;

            const props = feature.properties || {};
            return {
                lat,
                lon,
                display: formatPlaceDisplay(
                    props.name || props.street || props.county || props.state_district,
                    props.city || props.county || props.district,
                    props.state,
                    'India'
                ) || 'Selected Location, India'
            };
        }

        function parseNominatimFeature(feature) {
            if (!feature) return null;

            const lat = Number(feature.lat);
            const lon = Number(feature.lon);
            if (!isWithinIndia(lat, lon)) return null;

            const address = feature.address || {};
            return {
                lat,
                lon,
                display: formatPlaceDisplay(
                    address.road || address.neighbourhood || address.suburb || address.village || address.town || address.city || feature.display_name?.split(',')?.[0],
                    address.city || address.town || address.village || address.county,
                    address.state,
                    'India'
                ) || feature.display_name || 'Selected Location, India'
            };
        }

        async function fetchPhotonLocation(query) {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 5000);

            try {
                const response = await fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=5&bbox=68,6,98,38`, {
                    signal: controller.signal
                });
                const data = await response.json();
                if (!Array.isArray(data.features)) return null;
                return data.features.map(parsePhotonFeature).find(Boolean) || null;
            } finally {
                clearTimeout(timeoutId);
            }
        }

        async function fetchNominatimLocation(query) {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 6000);

            try {
                const params = new URLSearchParams({
                    q: query,
                    format: 'jsonv2',
                    addressdetails: '1',
                    limit: '5',
                    countrycodes: 'in',
                    bounded: '1',
                    viewbox: '68,38,98,6'
                });

                const response = await fetch(`https://nominatim.openstreetmap.org/search?${params.toString()}`, {
                    signal: controller.signal,
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (!Array.isArray(data)) return null;
                return data.map(parseNominatimFeature).find(Boolean) || null;
            } finally {
                clearTimeout(timeoutId);
            }
        }

        async function reverseGeocode(lat, lon) {
            if (!isWithinIndia(lat, lon)) return null;

            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 6000);

            try {
                const params = new URLSearchParams({
                    lat: String(lat),
                    lon: String(lon),
                    format: 'jsonv2',
                    addressdetails: '1',
                    zoom: '16'
                });

                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?${params.toString()}`, {
                    signal: controller.signal,
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                return parseNominatimFeature(data) || {
                    lat,
                    lon,
                    display: `Pinned Location (${lat.toFixed(4)}, ${lon.toFixed(4)})`
                };
            } catch (error) {
                console.error('Reverse geocode failed:', error);
                return {
                    lat,
                    lon,
                    display: `Pinned Location (${lat.toFixed(4)}, ${lon.toFixed(4)})`
                };
            } finally {
                clearTimeout(timeoutId);
            }
        }

        /**
         * Robust geocoding with multiple fallback strategies and timeout
         */
        async function geocode(q) {
            // Resolves user-entered locations using local aliases and external geocoding fallbacks.
            const canonicalInput = resolveCanonicalLocationInput(q);
            const queries = buildGeocodeQueries(canonicalInput);
            const localMatch = getLocalGeocodeResult(canonicalInput);

            if (localMatch) {
                return {
                    lat: localMatch.lat,
                    lon: localMatch.lon,
                    display: localMatch.display
                };
            }

            for (const query of queries) {
                const queryLocalMatch = getLocalGeocodeResult(query);
                if (queryLocalMatch) {
                    return {
                        lat: queryLocalMatch.lat,
                        lon: queryLocalMatch.lon,
                        display: queryLocalMatch.display
                    };
                }
                try {
                    const photonMatch = await fetchPhotonLocation(query);
                    if (photonMatch) return photonMatch;

                    const nominatimMatch = await fetchNominatimLocation(query);
                    if (nominatimMatch) return nominatimMatch;
                } catch(e) { 
                    console.error(`Geocoding failed for ${query}:`, e); 
                }
            }
            return null;
        }

        function calculateDistanceKm(start, end) {
            const toRad = deg => deg * (Math.PI / 180);
            const earthRadiusKm = 6371;
            const dLat = toRad(end.lat - start.lat);
            const dLon = toRad(end.lng - start.lng);
            const lat1 = toRad(start.lat);
            const lat2 = toRad(end.lat);

            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.sin(dLon / 2) * Math.sin(dLon / 2) * Math.cos(lat1) * Math.cos(lat2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return earthRadiusKm * c;
        }

        function clearFallbackRouteLine() {
            if (fallbackRouteLine) {
                map.removeLayer(fallbackRouteLine);
                fallbackRouteLine = null;
            }
        }

        function renderRouteSummary(distanceKm, price, approximate = false) {
            currentDistance = `${distanceKm.toFixed(1)} km`;
            currentRoutePrice = price;

            addMsg(`<div class="bg-primary/5 -mx-4 -mt-3 p-4 rounded-t-[1.25rem] border-b border-primary/10 mb-3">
                    <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-1">${isHistoryView ? 'Trip Replay' : 'Route Ready'}</p>
                    <h4 class="text-lg font-black text-slate-900 leading-tight">${approximate ? 'Using a direct route estimate for this trip.' : (isHistoryView ? 'Here is the route from your trip history.' : 'Found the best path!')}</h4>
                </div>
                <div class="space-y-2 pb-1">
                    <div class="flex justify-between items-center"><span class="text-slate-400 font-bold uppercase text-[10px] tracking-wider">Distance</span><span class="text-slate-900 font-black">${currentDistance}</span></div>
                    <div class="flex justify-between items-center"><span class="text-slate-400 font-bold uppercase text-[10px] tracking-wider">${approximate ? 'Approx Fare' : 'Estimated Fare'}</span><span class="text-primary font-black text-base">₹${price}</span></div>
                </div>`, 'bot');

            syncRouteSchedulePrompt();
            syncRouteActionButtons();
        }

        function renderFallbackRoute() {
            // Builds an approximate route summary when live routing is unavailable.
            removeTyping(document.getElementById('typing-indicator'));
            clearFallbackRouteLine();

            if (!pickupCoords || !dropCoords) return;

            const distanceKm = calculateDistanceKm(pickupCoords, dropCoords);
            const price = (distanceKm * 15).toFixed(2);

            fallbackRouteLine = L.polyline([pickupCoords, dropCoords], {
                color: '#FF4B4B',
                weight: 6,
                opacity: 0.75,
                dashArray: '10, 10'
            }).addTo(map);

            document.getElementById('routeDetails').innerHTML = `
                <div class="sticky top-0 z-10 bg-white/95 backdrop-blur-md p-6 border-b border-slate-100 flex justify-between items-center shadow-sm">
                    <div>
                        <p class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Direct Route</p>
                        <h4 class="font-extrabold text-slate-800 text-lg leading-tight truncate w-48">${dropLoc}</h4>
                        <p class="text-[11px] text-slate-400 mt-1 font-bold uppercase tracking-wider">Approximate distance preview</p>
                    </div>
                    <button onclick="toggleRouteDetails()" class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors border border-slate-100"><i class="fas fa-times"></i></button>
                </div>
                <div class="flex-1 overflow-y-auto no-scrollbar p-4 space-y-2 bg-slate-50">
                    <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-[12px] font-bold text-slate-700 leading-relaxed">Detailed road routing is unavailable right now, so we prepared a direct trip estimate to keep your booking moving.</p>
                    </div>
                </div>
            `;

            map.fitBounds(L.latLngBounds([pickupCoords, dropCoords]), { padding: [100, 100] });
            renderRouteSummary(distanceKm, price, true);
        }

        /**
         * Main chat handler for user input
         */
        async function sendMessage() {
            // Advances the assistant flow by resolving pickup and destination messages.
            if(isFetching) return;
            const i = document.getElementById('chatInput'), m = i.value.trim();
            if(!m) return;

            isFetching = true;
            disablePreviousActions();
            addMsg(m, 'user'); i.value = ""; i.disabled = true;
            
            const typing = showTyping();
            
            try {
                if(chatStep === 0) {
                    const r = await geocode(m); i.disabled = false;
                    removeTyping(typing);
                    
                    if(r) {
                        pickupCoords = L.latLng(r.lat, r.lon); pickupLoc = r.display; chatStep = 1;
                        addMsg(`📍 <b>Pickup set:</b> ${r.display}.<br>Now, where would you like to go?`, 'bot');
                        if (routeControl) map.removeControl(routeControl);
                        L.marker(pickupCoords, { icon: L.divIcon({ className: 'custom-icon', html: `<div class='bg-emerald-500 w-10 h-10 rounded-full shadow-lg border-4 border-white flex items-center justify-center text-white scale-75'><i class='fas fa-circle text-[10px]'></i></div>` }) }).addTo(map);
                        map.setView(pickupCoords, 14);
                    } else {
                        addMsg("❌ I couldn't find that location. Could you try being more specific? (e.g., 'Railway Station, City')", 'bot');
                    }
                } else {
                    const r = await geocode(m); i.disabled = false;
                    removeTyping(typing);
                    
                    if(r) {
                        dropCoords = L.latLng(r.lat, r.lon); dropLoc = r.display; isHistoryView = false; applyHistoryModeContent();
                        addMsg(`🏁 <b>Destination:</b> ${r.display}.<br>Calculating your route now...`, 'bot');
                        calculateRoute(); chatStep = 0;
                    } else {
                        addMsg("❌ I couldn't find your destination. Please try another name or keep it simple.", 'bot');
                    }
                }
            } catch(error) {
                console.error("Chat error:", error);
                removeTyping(typing);
                addMsg("⚠️ <b>Something went wrong.</b> Please check your connection and try again.", 'bot');
                i.disabled = false;
            } finally {
                isFetching = false;
            }
        }

        /**
         * Appends message bubble to chat box with premium styling
         */
        function addMsg(t, s) {
            const d = document.createElement('div');
            const align = s === 'bot' ? 'justify-start' : 'justify-end';
            const isAction = t.includes('flex gap-');
            
            const bg = s === 'bot' 
                ? (isAction ? 'bg-transparent' : 'bg-white/95 backdrop-blur-md border border-slate-200 text-slate-800 rounded-tl-sm shadow-sm') 
                : 'bg-slate-800 text-white rounded-tr-sm shadow-md border border-slate-900';
            
            d.className = `flex ${align} animate-in fade-in slide-in-from-bottom-2 duration-300 mb-2`;
            d.innerHTML = `<div class="${bg} ${isAction ? 'p-1 w-full border-none' : 'px-4 py-3 min-w-[60px] max-w-[85%]'} rounded-[1.25rem] text-[13px] font-medium leading-relaxed">${t}</div>`;
            
            const box = document.getElementById('chatBox');
            box.appendChild(d);
            box.scrollTop = box.scrollHeight;
        }

        function formatPickupSchedule(dateValue, timeValue) {
            const scheduleDate = new Date(`${dateValue}T${timeValue}`);

            const prettyDate = scheduleDate.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });

            const prettyTime = scheduleDate.toLocaleTimeString('en-IN', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            return `${prettyDate} at ${prettyTime}`;
        }

        function isValidPickupTime(timeValue) {
            return /^([01]\d|2[0-3]):([0-5]\d)$/.test(timeValue);
        }

        function syncPickupScheduleMessage() {
            const pickupDateInput = document.getElementById('pickupDate');
            const pickupTimeInput = document.getElementById('pickupTime');
            const chatBox = document.getElementById('chatBox');

            if (!pickupDateInput || !pickupTimeInput || !chatBox) return;

            const dateValue = pickupDateInput.value;
            const timeValue = pickupTimeInput.value;
            const existingMessage = document.getElementById('pickupScheduleMsg');

            if (!dateValue || !timeValue) {
                if (existingMessage) {
                    existingMessage.remove();
                }
                syncRouteSchedulePrompt();
                syncRouteActionButtons();
                return;
            }

            const scheduleText = formatPickupSchedule(dateValue, timeValue);
            const wrapper = existingMessage || document.createElement('div');

            wrapper.id = 'pickupScheduleMsg';
            wrapper.className = 'flex justify-start animate-in fade-in slide-in-from-bottom-2 duration-300 mb-2';
            wrapper.innerHTML = `
                <div class="bg-white/95 backdrop-blur-md border border-slate-200 text-slate-800 rounded-[1.25rem] rounded-tl-sm shadow-sm px-4 py-3 min-w-[60px] max-w-[85%] text-[13px] font-medium leading-relaxed">
                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-primary mb-1">${isHistoryView ? 'Rebooking Schedule' : 'Pickup Schedule'}</p>
                    <p>${isHistoryView ? `This route is set to be booked again for <b>${scheduleText}</b>.` : `Your pickup is set for <b>${scheduleText}</b>.`}</p>
                </div>
            `;

            if (!existingMessage) {
                chatBox.appendChild(wrapper);
            }

            chatBox.scrollTop = chatBox.scrollHeight;
            syncRouteSchedulePrompt();
            syncRouteActionButtons();
        }

        function syncRouteActionButtons() {
            const chatBox = document.getElementById('chatBox');
            const existingButtons = document.getElementById('routeActionButtonsMsg');
            const selectedDate = document.getElementById('pickupDate')?.value || '';
            const selectedTime = document.getElementById('pickupTime')?.value || '';

            if (!chatBox) return;

            const canShowButtons = Boolean(currentRoutePrice && selectedDate && selectedTime && isValidPickupTime(selectedTime));

            if (!canShowButtons) {
                if (existingButtons) {
                    existingButtons.remove();
                }
                return;
            }

            const label = isHistoryView ? "Book Again" : "Confirm Ride";
            const wrapper = existingButtons || document.createElement('div');

            wrapper.id = 'routeActionButtonsMsg';
            wrapper.className = 'flex justify-start animate-in fade-in slide-in-from-bottom-2 duration-300 mb-2';
            wrapper.innerHTML = `
                <div class="bg-transparent p-1 w-full rounded-[1.25rem] text-[13px] font-medium leading-relaxed">
                    <div class="w-full mt-2 mb-1">
                        <button onclick="openPayment('${currentRoutePrice}')" class='w-full bg-primary text-white py-3.5 rounded-xl text-[13px] font-black hover:bg-red-600 transition-all shadow-md hover:shadow-primary/30 hover:-translate-y-0.5'>${label}</button>
                    </div>
                </div>
            `;

            if (!existingButtons) {
                chatBox.appendChild(wrapper);
            }

            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function syncRouteSchedulePrompt() {
            const chatBox = document.getElementById('chatBox');
            const existingPrompt = document.getElementById('routeSchedulePromptMsg');
            const selectedDate = document.getElementById('pickupDate')?.value || '';
            const selectedTime = document.getElementById('pickupTime')?.value || '';

            if (!chatBox || !currentRoutePrice) {
                if (existingPrompt) {
                    existingPrompt.remove();
                }
                return;
            }

            const needsPrompt = !selectedDate || !selectedTime || !isValidPickupTime(selectedTime);

            if (!needsPrompt) {
                if (existingPrompt) {
                    existingPrompt.remove();
                }
                return;
            }

            const wrapper = existingPrompt || document.createElement('div');
            wrapper.id = 'routeSchedulePromptMsg';
            wrapper.className = 'flex justify-start animate-in fade-in slide-in-from-bottom-2 duration-300 mb-2';
            wrapper.innerHTML = `
                <div class="bg-white/95 backdrop-blur-md border border-slate-200 text-slate-800 rounded-[1.25rem] rounded-tl-sm shadow-sm px-4 py-3 min-w-[60px] max-w-[85%] text-[13px] font-medium leading-relaxed">
                    ${isHistoryView ? 'Choose a pickup date and pickup time if you want to book this same route again.' : 'Please select both pickup date and pickup time before confirming your booking.'}
                </div>
            `;

            if (!existingPrompt) {
                chatBox.appendChild(wrapper);
            }

            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function setupPickupTimeFormatting() {
            const pickupTimeInput = document.getElementById('pickupTime');

            if (!pickupTimeInput) return;

            pickupTimeInput.addEventListener('input', function () {
                let value = this.value.replace(/\D/g, '').slice(0, 4);

                if (value.length >= 3) {
                    value = `${value.slice(0, 2)}:${value.slice(2)}`;
                }

                this.value = value;
            });
        }

        function formatNotificationTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        let bookingNotificationPoller = null;
        const bookingNotificationReadKey = 'cab_app_read_booking_notifications';
        const bookingNotificationShownKey = 'cab_app_shown_booking_notifications';

        function getStoredBookingNotificationIds(key) {
            try {
                const parsed = JSON.parse(localStorage.getItem(key) || '[]');
                return Array.isArray(parsed) ? parsed.map(id => Number(id)) : [];
            } catch (error) {
                return [];
            }
        }

        function storeBookingNotificationId(key, id) {
            if (!id) return;

            const stored = new Set(getStoredBookingNotificationIds(key));
            stored.add(Number(id));
            localStorage.setItem(key, JSON.stringify(Array.from(stored).slice(-30)));
        }

        function markBookingNotificationRead(id) {
            storeBookingNotificationId(bookingNotificationReadKey, id);
        }

        function markBookingNotificationShown(id) {
            storeBookingNotificationId(bookingNotificationShownKey, id);
        }

        function openAssignedDriverModal(booking) {
            const modal = document.getElementById('driverModal');
            if (!modal || !booking) return;

            document.getElementById('assignedPickup').textContent = booking.pickup_location || '---';
            document.getElementById('assignedDest').textContent = booking.destination || '---';
            document.getElementById('assignedDriverName').textContent = booking.driver_name || 'Assigned Driver';
            document.getElementById('assignedCabModel').textContent = booking.cab_model || 'Assigned Cab';
            document.getElementById('assignedCabNumber').textContent = booking.cab_number || '---';
            document.getElementById('assignedDriverContact').innerHTML = `<i class="fas fa-phone-alt text-[10px]"></i> ${booking.driver_contact || 'N/A'}`;
            document.getElementById('bookingOTP').textContent = booking.otp || String(booking.id || '').padStart(4, '0').slice(-4);

            modal.classList.remove('hidden');
        }

        async function loadBookingNotifications() {
            // Fetches confirmed booking notifications and updates the assistant inbox state.
            const box = document.getElementById('notificationBox');
            const badge = document.getElementById('notificationBadge');

            if (!box) return;

            box.innerHTML = `
                <div class="bg-white border border-slate-200 text-slate-700 px-4 py-3 rounded-[1rem] text-[12px] font-medium leading-relaxed">
                    Loading booking notifications...
                </div>
            `;

            try {
                const response = await fetch('api/get_booking_notifications.php');
                const data = await response.json();

                if (!data.success || !Array.isArray(data.notifications) || data.notifications.length === 0) {
                    box.innerHTML = `
                        <div class="bg-white border border-slate-200 text-slate-700 px-4 py-3 rounded-[1rem] text-[12px] font-medium leading-relaxed">
                            No booking approval notifications yet.
                        </div>
                    `;
                    if (badge) badge.classList.add('hidden');
                    return;
                }

                const readNotificationIds = new Set(getStoredBookingNotificationIds(bookingNotificationReadKey));
                const shownNotificationIds = new Set(getStoredBookingNotificationIds(bookingNotificationShownKey));
                const newestUnreadNotification = data.notifications.find(item => !readNotificationIds.has(Number(item.id)));
                const newestUnshownNotification = data.notifications.find(item => !shownNotificationIds.has(Number(item.id)));

                box.innerHTML = data.notifications.map(item => `
                    <div class="bg-white border border-slate-200 rounded-[1.15rem] p-4 shadow-sm" data-notification-id="${item.id}">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.18em] text-emerald-600">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                Booking Approved
                            </div>
                            <span class="text-[10px] font-bold text-slate-400">${formatNotificationTime(item.created_at)}</span>
                        </div>
                        <div class="space-y-2 text-[12px] text-slate-700 font-medium">
                            <p><span class="font-black text-slate-900">Driver:</span> ${item.driver_name}</p>
                            <p><span class="font-black text-slate-900">Contact:</span> ${item.driver_contact}</p>
                            <p><span class="font-black text-slate-900">Cab:</span> ${item.cab_model}</p>
                            <p><span class="font-black text-slate-900">Cab Number:</span> ${item.cab_number}</p>
                            <p><span class="font-black text-slate-900">Pickup OTP:</span> ${item.otp || '0000'}</p>
                            <p><span class="font-black text-slate-900">Route:</span> ${item.pickup_location} to ${item.destination}</p>
                        </div>
                    </div>
                `).join('');

                if (badge) {
                    if (newestUnreadNotification) {
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }

                if (newestUnshownNotification) {
                    openAssignedDriverModal(newestUnshownNotification);
                    markBookingNotificationShown(newestUnshownNotification.id);
                }
            } catch (error) {
                console.error('Notification error:', error);
                box.innerHTML = `
                    <div class="bg-white border border-red-100 text-red-500 px-4 py-3 rounded-[1rem] text-[12px] font-medium leading-relaxed">
                        Failed to load booking notifications.
                    </div>
                `;
            }
        }

        function toggleNotificationInbox(forceState = null) {
            const chatView = document.getElementById('assistantChatView');
            const notificationView = document.getElementById('assistantNotificationView');

            isNotificationView = forceState === null ? !isNotificationView : forceState;

            if (isNotificationView) {
                chatView.classList.add('hidden');
                notificationView.classList.remove('hidden');
                loadBookingNotifications().then(() => {
                    document.querySelectorAll('#notificationBox [data-notification-id]').forEach(node => {
                        markBookingNotificationRead(node.dataset.notificationId);
                    });
                    const badge = document.getElementById('notificationBadge');
                    if (badge) badge.classList.add('hidden');
                });
            } else {
                notificationView.classList.add('hidden');
                chatView.classList.remove('hidden');
            }
        }

        /**
         * Calculates route, displays path on map, and populates itinerary
         */
        function calculateRoute() {
            // Calculates the live route, fare summary, and turn list for the current trip.
            if (routeControl) map.removeControl(routeControl);
            if (stepMarker) map.removeLayer(stepMarker);
            clearFallbackRouteLine();
            currentRoutePrice = "";
            syncRouteSchedulePrompt();
            syncRouteActionButtons();
            
            const routeTyping = showTyping();
            
            try {
                routeControl = L.Routing.control({
                    waypoints: [pickupCoords, dropCoords],
                    lineOptions: { styles: [{color: '#FF4B4B', weight: 6, opacity: 0.8}] },
                    createMarker: (i, wp) => L.marker(wp.latLng, { icon: L.divIcon({ className: 'custom-icon', html: `<div class='bg-white w-8 h-8 rounded-xl shadow-xl border-2 ${i === 0 ? "border-emerald-500" : "border-primary"} flex items-center justify-center'><i class='fas ${i === 0 ? "fa-circle-dot" : "fa-location-dot"} text-[12px] ${i === 0 ? "text-emerald-500" : "text-primary"}'></i></div>` }) }),
                    show: false, addWaypoints: false, fitSelectedRoutes: true
                }).on('routesfound', function(e) {
                    removeTyping(routeTyping);
                    const r = e.routes[0], dist = r.summary.totalDistance / 1000, price = (dist * 15).toFixed(2);
                    currentDistance = dist.toFixed(1) + " km";
                    currentRoutePrice = price;
                    
                    addMsg(`<div class="bg-primary/5 -mx-4 -mt-3 p-4 rounded-t-[1.25rem] border-b border-primary/10 mb-3">
                            <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-1">${isHistoryView ? 'Trip Replay' : 'Route Ready'}</p>
                            <h4 class="text-lg font-black text-slate-900 leading-tight">${isHistoryView ? 'Here is the route from your trip history.' : 'Found the best path!'}</h4>
                        </div>
                        <div class="space-y-2 pb-1">
                            <div class="flex justify-between items-center"><span class="text-slate-400 font-bold uppercase text-[10px] tracking-wider">Distance</span><span class="text-slate-900 font-black">${currentDistance}</span></div>
                            <div class="flex justify-between items-center"><span class="text-slate-400 font-bold uppercase text-[10px] tracking-wider">Estimated Fare</span><span class="text-primary font-black text-base">₹${price}</span></div>
                        </div>`, 'bot');
                    syncRouteSchedulePrompt();
                    
                    const header = `<div class="sticky top-0 z-10 bg-white/95 backdrop-blur-md p-6 border-b border-slate-100 flex justify-between items-center shadow-sm">
                        <div><p class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Trip Itinerary</p><h4 class="font-extrabold text-slate-800 text-lg leading-tight truncate w-48">${dropLoc}</h4><p class="text-[11px] text-slate-400 mt-1 font-bold uppercase tracking-wider">${dist.toFixed(1)} km journey</p></div>
                        <button onclick="toggleRouteDetails()" class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors border border-slate-100"><i class="fas fa-times"></i></button>
                    </div>`;
                    
                    let steps = `<div class="flex-1 overflow-y-auto no-scrollbar p-4 space-y-2 bg-slate-50">`;
                    r.instructions.forEach((s, idx) => {
                        const bg = (idx === 0) ? 'bg-emerald-500' : (idx === r.instructions.length - 1 ? 'bg-primary' : 'bg-slate-300');
                        steps += `<div onclick="highlightStep(${r.coordinates[s.index].lat}, ${r.coordinates[s.index].lng})" class="flex items-center gap-4 p-4 bg-white hover:border-primary/30 cursor-pointer rounded-2xl border border-slate-100 transition-all shadow-sm group">
                            <div class="w-2.5 h-2.5 rounded-full ${bg} shadow-md flex-shrink-0 group-hover:scale-125 transition-transform"></div>
                            <div class="flex-1 text-[13px] font-bold text-slate-700 leading-snug group-hover:text-slate-900">${s.text}<br><span class="text-[10px] text-slate-400 uppercase tracking-widest font-black mt-1 inline-block">${s.distance < 1000 ? Math.round(s.distance) + 'm' : (s.distance / 1000).toFixed(1) + 'km'}</span></div>
                        </div>`;
                    });
                    
                    document.getElementById('routeDetails').innerHTML = header + steps + `</div>`;

                    syncRouteActionButtons();
                    map.fitBounds(L.latLngBounds([pickupCoords, dropCoords]), { padding: [100, 100] });
                }).on('routingerror', function(error) {
                    console.error("Routing error:", error);
                    removeTyping(routeTyping);
                    renderFallbackRoute();
                }).addTo(map);
            } catch (error) {
                console.error("Route calculation error:", error);
                removeTyping(routeTyping);
                return renderFallbackRoute();
            }
        }

        /**
         * Map focus on a specific itinerary step
         */
        function highlightStep(lat, lng) {
            if (stepMarker) map.removeLayer(stepMarker);
            stepMarker = L.circleMarker([lat, lng], { radius: 10, fillColor: "#FF4B4B", color: "white", weight: 4, opacity: 1, fillOpacity: 1 }).addTo(map);
            map.panTo([lat, lng]);
        }

        function toggleRouteDetails() { 
            const el = document.getElementById('routeDetails');
            if(el.classList.contains('hidden')) {
                el.classList.remove('hidden');
                el.classList.add('animate-in', 'slide-in-from-left-4', 'fade-in');
            } else {
                el.classList.add('hidden');
            }
        }

        map.on('click', async function(e) {
            if (!isLoggedIn || isFetching) return;

            const lat = e.latlng.lat;
            const lon = e.latlng.lng;

            if (!isWithinIndia(lat, lon)) {
                addMsg("⚠️ Please select a point within India to build your trip.", 'bot');
                return;
            }

            isFetching = true;
            const typing = showTyping();

            try {
                const result = await reverseGeocode(lat, lon);
                removeTyping(typing);

                if (!result) {
                    addMsg("❌ I couldn't read that map point clearly. Please try another spot.", 'bot');
                    return;
                }

                if (chatStep === 0 || !pickupCoords) {
                    pickupCoords = L.latLng(result.lat, result.lon);
                    pickupLoc = result.display;
                    chatStep = 1;
                    addMsg(`📍 <b>Pickup pinned:</b> ${result.display}.<br>Now select or type your destination.`, 'bot');
                    map.setView(pickupCoords, 13);
                } else {
                    dropCoords = L.latLng(result.lat, result.lon);
                    dropLoc = result.display;
                    chatStep = 0;
                    isHistoryView = false;
                    applyHistoryModeContent();
                    addMsg(`🏁 <b>Destination pinned:</b> ${result.display}.<br>Calculating your route now...`, 'bot');
                    calculateRoute();
                }
            } catch (error) {
                removeTyping(typing);
                console.error('Map selection error:', error);
                addMsg("⚠️ I couldn't use that map point right now. Please try again.", 'bot');
            } finally {
                isFetching = false;
            }
        });

        /**
         * Assistant Interface Toggle
         */
        let isChatOpen = false;
        function toggleChat() { 
            // Switches the right panel between the placeholder and assistant chat views.
            const chat = document.getElementById('chatInterface');
            const placeholder = document.getElementById('panelPlaceholder');
            
            isChatOpen = !isChatOpen;
            if(isChatOpen) {
                toggleNotificationInbox(false);
                chat.classList.remove('opacity-0', 'pointer-events-none', 'translate-x-4');
                chat.classList.add('z-10');
                placeholder.classList.add('opacity-0', 'pointer-events-none', '-translate-x-4');
                placeholder.classList.remove('z-10');
                document.getElementById('chatInput').focus();
            } else {
                chat.classList.add('opacity-0', 'pointer-events-none', 'translate-x-4');
                chat.classList.remove('z-10');
                placeholder.classList.remove('opacity-0', 'pointer-events-none', '-translate-x-4');
                placeholder.classList.add('z-10');
            }
        }

        /**
         * Prepares payment modal
         */
        function openPayment(p) {
            // Opens the payment modal after pickup schedule validation succeeds.
            if (!isLoggedIn) {
                window.location.href = 'auth.php?mode=login';
                return;
            }

            const selectedDate = document.getElementById('pickupDate').value;
            const selectedTime = document.getElementById('pickupTime').value;

            if (!selectedDate || !selectedTime) {
                addMsg("⚠️ Please select both pickup date and pickup time before confirming your booking.", 'bot');
                if (!selectedDate) {
                    document.getElementById('pickupDate').focus();
                } else {
                    document.getElementById('pickupTime').focus();
                }
                return;
            }

            if (!isValidPickupTime(selectedTime)) {
                addMsg("⚠️ Please enter pickup time in HH:MM format, for example 00:00 or 14:30.", 'bot');
                document.getElementById('pickupTime').focus();
                return;
            }

            syncPickupScheduleMessage();
            document.getElementById('finalPrice').innerText = "₹" + p;
            selectPayment('cash');
            document.getElementById('paymentModal').classList.remove('hidden');
        }
        
        function selectPayment(type) {
            const casBtn = document.getElementById('payCashBtn'), crdBtn = document.getElementById('payCardBtn');
            const casChk = document.getElementById('cashCheck'), crdChk = document.getElementById('cardCheck');
            const cardF = document.getElementById('cardFields');
            if(type === 'cash') {
                casBtn.className = "bg-primary/5 border-2 border-primary shadow-sm p-4 rounded-2xl flex items-center justify-between cursor-pointer transition-all";
                crdBtn.className = "bg-white border border-slate-200 shadow-sm p-4 rounded-2xl flex items-center justify-between cursor-pointer hover:border-slate-300 transition-all";
                casChk.className = "text-primary text-xl"; casChk.innerHTML = '<i class="fas fa-check-circle"></i>';
                crdChk.className = "text-slate-200 text-xl"; crdChk.innerHTML = '<i class="far fa-circle"></i>';
                cardF.classList.add('hidden');
            } else {
                crdBtn.className = "bg-primary/5 border-2 border-primary shadow-sm p-4 rounded-2xl flex items-center justify-between cursor-pointer transition-all";
                casBtn.className = "bg-white border border-slate-200 shadow-sm p-4 rounded-2xl flex items-center justify-between cursor-pointer hover:border-slate-300 transition-all";
                crdChk.className = "text-primary text-xl"; crdChk.innerHTML = '<i class="fas fa-check-circle"></i>';
                casChk.className = "text-slate-200 text-xl"; casChk.innerHTML = '<i class="far fa-circle"></i>';
                cardF.classList.remove('hidden');
            }
        }

        function closePayment() { document.getElementById('paymentModal').classList.add('hidden'); }

        /**
         * Finalizes booking.
         */
        async function confirmBooking() {
            // Sends the finalized trip details to the booking API after payment review.
            if (!isLoggedIn || isFetching) return;
            
            if (!pickupLoc || !dropLoc) {
                addMsg("⚠️ I seem to have lost your route details. Could you please select them again?", 'bot');
                closePayment();
                return;
            }

            isFetching = true;
            const p = document.getElementById('finalPrice').innerText.replace('₹', '');
            const selectedDate = document.getElementById('pickupDate').value;
            const selectedTime = document.getElementById('pickupTime').value;

            if (!selectedDate || !selectedTime) {
                isFetching = false;
                closePayment();
                addMsg("⚠️ Pickup date and pickup time are mandatory before booking. Please select both and try again.", 'bot');
                return;
            }

            if (!isValidPickupTime(selectedTime)) {
                isFetching = false;
                closePayment();
                addMsg("⚠️ Pickup time must be in HH:MM format, for example 00:00 or 14:30.", 'bot');
                document.getElementById('pickupTime').focus();
                return;
            }

            syncPickupScheduleMessage();
            const scheduleText = formatPickupSchedule(selectedDate, selectedTime);
            
            const typing = showTyping();
            try {
                const body = JSON.stringify({ 
                    pickup: pickupLoc, 
                    dest: dropLoc, 
                    fare: p, 
                    distance: currentDistance,
                    date: selectedDate,
                    time: selectedTime
                });
                const r = await fetch('api/book_ride.php', { method: 'POST', body });
                const res = await r.json();
                
                removeTyping(typing);
                closePayment();

                if(res.success) { 
                    addMsg(`<div class="bg-emerald-500/10 -mx-4 -mt-3 p-4 rounded-t-[1.25rem] border-b border-emerald-500/10 mb-3">
                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Booking Confirmed</p>
                            <h4 class="text-base font-black text-slate-900 leading-tight">Your request is sent!</h4>
                        </div>
                        <p class="text-[13px] font-medium text-slate-600 leading-relaxed">
                            Your ride is currently <b>pending approval</b>. Pickup is scheduled for <b>${scheduleText}</b>. We'll notify you as soon as a driver is assigned.
                        </p>`, 'bot'); 
                } else {
                    addMsg("❌ <b>Oops!</b> " + (res.message || "Something went wrong with the booking."), 'bot');
                }
            } catch(e) { 
                removeTyping(typing);
                addMsg("❌ <b>Network Error!</b> I couldn't reach the server. Please check your connection.", 'bot');
            } finally {
                isFetching = false;
            }
        }

        /**
         * Loads past ride history for the user
         */
        async function loadHistory() {
            // Loads the ride history cards shown inside the assistant bubble.
            const cont = document.getElementById('ridesContent'); 
            cont.innerHTML = '<div class="text-center py-20"><div class="w-10 h-10 border-4 border-slate-200 border-t-primary rounded-full animate-spin mx-auto mb-4"></div><p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Loading History</p></div>';
            try {
                const r = await fetch('api/get_rides.php'), d = await r.json();
                if(d.success) {
                    cont.innerHTML = d.rides.map(ride => {
                        const driverInfo = ride.driver_name ? `
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 border border-slate-200 shadow-inner"><i class="fas fa-user-tie"></i></div>
                                        <div>
                                            <p class="text-[11px] font-black text-slate-800">${ride.driver_name}</p>
                                            <p class="text-[10px] text-slate-400 font-bold tracking-tight">${ride.driver_contact}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-black text-slate-900 uppercase tracking-widest">${ride.cab_model}</p>
                                        <p class="text-[9px] font-black text-primary uppercase tracking-widest mt-0.5 px-2 py-0.5 bg-primary/5 rounded border border-primary/10">${ride.cab_number}</p>
                                    </div>
                                </div>
                            </div>` : '';
                        
                        return `
                        <div onclick="viewHistoryRoute('${ride.pickup_location.replace(/'/g, "\\'")}', '${ride.destination.replace(/'/g, "\\'")}')" class="bg-white p-6 rounded-[2rem] border border-slate-100 hover:border-primary/20 hover:shadow-xl hover:shadow-primary/5 transition-all group cursor-pointer relative overflow-hidden">
                            <div class="flex justify-between items-start mb-4">
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.15em]">${new Date(ride.created_at).toLocaleDateString()}</span>
                                <span class="bg-primary/5 text-primary text-[11px] font-black px-3 py-1 rounded-full border border-primary/10">₹${ride.fare}</span>
                            </div>
                            <div class="space-y-3 relative">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0 shadow-sm shadow-emerald-500/50"></div>
                                    <p class="text-[13px] font-bold text-slate-700 truncate">${ride.pickup_location}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0 shadow-sm shadow-primary/50"></div>
                                    <p class="text-[13px] font-bold text-slate-400 truncate group-hover:text-slate-900 transition-colors">${ride.destination}</p>
                                </div>
                            </div>
                            ${driverInfo}
                        </div>`;
                    }).join('') || '<div class="text-center py-20 bg-slate-50 rounded-[2rem] border border-dashed border-slate-200"><p class="text-slate-400 font-black uppercase tracking-widest text-[10px]">Your journey starts here</p></div>';
                }
            } catch(e) { console.error("History error:", e); }
        }

        /**
         * Displays a route from history on the map
         */
        async function viewHistoryRoute(p, d) {
            // Replays a historical route on the map and in the assistant timeline.
            if(typeof closeBubble === 'function') closeBubble();
            isHistoryView = true;
            applyHistoryModeContent();
            addMsg(`Re-visualizing your journey from <b>${p.split(',')[0]}</b> to <b>${d.split(',')[0]}</b>...`, 'bot');
            pickupLoc = p; dropLoc = d;
            
            const historyTyping = showTyping();
            const pRes = await geocode(p), dRes = await geocode(d);
            removeTyping(historyTyping);
            
            if(pRes && dRes) {
                pickupCoords = L.latLng(pRes.lat, pRes.lon); dropCoords = L.latLng(dRes.lat, dRes.lon);
                calculateRoute();
            } else {
                addMsg("❌ I couldn't map that route right now. The address might be outdated.", 'bot');
            }
        }

        /**
         * Formats card payment inputs
         */
        function setupCardFormatting() {
            // Keeps card number, expiry, and CVV inputs in a strict visual format.
            const cardNum = document.getElementById('cardNumber');
            const cardExp = document.getElementById('cardExpiry');
            const cardCvv = document.getElementById('cardCvv');

            if(cardNum) {
                cardNum.oninput = function(e) {
                    let v = this.value.replace(/\D/g, '').substring(0, 16);
                    v = v.match(/.{1,4}/g)?.join(' ') || v;
                    this.value = v;
                };
            }

            if(cardExp) {
                cardExp.oninput = function(e) {
                    let v = this.value.replace(/\D/g, '').substring(0, 4);
                    if (e.inputType !== 'deleteContentBackward') {
                        if (v.length === 1 && parseInt(v) > 1) v = '0' + v;
                        if (v.length === 2) {
                            let mm = parseInt(v);
                            if (mm > 12) v = '12';
                            else if (mm === 0) v = '01';
                        }
                    }
                    if (v.length >= 2) v = v.substring(0, 2) + '/' + v.substring(2);
                    this.value = v;
                };
            }

            if(cardCvv) {
                cardCvv.oninput = function(e) {
                    this.value = this.value.replace(/\D/g, '').substring(0, 3);
                };
            }
        }
        
        // Initialize fresh state
        window.addEventListener('DOMContentLoaded', () => {
            clearPersistedChat();
            applyHistoryModeContent();
            setupCardFormatting();
            setupPickupTimeFormatting();
            document.getElementById('pickupDate')?.addEventListener('change', syncPickupScheduleMessage);
            document.getElementById('pickupTime')?.addEventListener('change', syncPickupScheduleMessage);

            if (isLoggedIn) {
                loadBookingNotifications();
                if (bookingNotificationPoller) clearInterval(bookingNotificationPoller);
                bookingNotificationPoller = setInterval(loadBookingNotifications, 10000);
            }
            
            const params = new URLSearchParams(window.location.search);
            if(params.get('pickup')) pickupLoc = decodeURIComponent(params.get('pickup'));
            if(params.get('dest')) dropLoc = decodeURIComponent(params.get('dest'));
            if(params.get('visualize') === 'true') {
                isHistoryView = true;
                applyHistoryModeContent();
            }
            
            if(params.get('history') === 'true') {
                if(typeof openBubble === 'function') openBubble('rides');
            }
            if(params.get('visualize') === 'true' && pickupLoc && dropLoc) {
                viewHistoryRoute(pickupLoc, dropLoc);
            } else if(pickupLoc) {
                document.getElementById('chatInput').value = pickupLoc;
            }
        });
    </script>

<?php include 'layout/footer.php'; ?>
