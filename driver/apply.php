<?php 
// Include the global header for navigation and consistent UI
include '../layout/header.php'; 
?>

    <style>
        .driver-stage {
            position: relative;
            overflow: hidden;
        }

        .driver-stage::before {
            content: '';
            position: absolute;
            top: 90px;
            left: 3%;
            width: 320px;
            height: 320px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.12) 0%, rgba(59, 130, 246, 0) 72%);
            pointer-events: none;
        }

        .driver-stage::after {
            content: '';
            position: absolute;
            right: -120px;
            bottom: -120px;
            width: 420px;
            height: 420px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(244, 63, 94, 0.1) 0%, rgba(244, 63, 94, 0) 72%);
            pointer-events: none;
        }

        .driver-copy-card {
            position: relative;
            padding: 2.35rem;
            border-radius: 2.2rem;
            background: rgba(255, 255, 255, 0.64);
            border: 1px solid rgba(226, 232, 240, 0.82);
            box-shadow: 0 18px 40px rgba(148, 163, 184, 0.12);
            backdrop-filter: blur(14px);
        }

        .driver-copy-card h2 {
            letter-spacing: -0.06em;
        }

        .driver-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.55rem 0.9rem;
            border-radius: 999px;
            background: rgba(255, 75, 75, 0.08);
            border: 1px solid rgba(255, 75, 75, 0.14);
            margin-bottom: 1.35rem;
        }

        .driver-pill span {
            color: #ef4444;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.24em;
        }

        .driver-benefit {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 0;
        }

        .driver-benefit + .driver-benefit {
            border-top: 1px solid rgba(226, 232, 240, 0.78);
        }

        .driver-benefit-icon {
            width: 58px;
            height: 58px;
            border-radius: 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(226, 232, 240, 0.92);
            box-shadow: 0 12px 25px rgba(148, 163, 184, 0.16);
            color: #ef4444;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .driver-form-shell {
            position: relative;
            border-radius: 2.5rem;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(226, 232, 240, 0.92);
            box-shadow: 0 28px 60px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(14px);
        }

        .driver-form-shell::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.14), rgba(244, 63, 94, 0.08));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .driver-panel-title {
            letter-spacing: -0.05em;
        }

        .driver-label {
            color: #64748b;
            font-size: 0.72rem;
            letter-spacing: 0.12em;
        }

        .driver-input,
        .driver-select-trigger,
        .driver-prefix-input,
        .driver-upload-box {
            border-radius: 1.35rem !important;
            border: 1px solid #e2e8f0 !important;
            background: linear-gradient(180deg, #f8fbff 0%, #f8fafc 100%) !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
        }

        .driver-input:focus,
        .driver-prefix-input:focus,
        .driver-select-trigger:hover {
            border-color: #94a3b8 !important;
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.05) !important;
            background: #fff !important;
        }

        .driver-submit {
            border-radius: 1.55rem !important;
            padding-top: 1.2rem !important;
            padding-bottom: 1.2rem !important;
            letter-spacing: 0.18em !important;
            box-shadow: 0 20px 34px rgba(15, 23, 42, 0.14) !important;
        }

        @media (max-width: 1024px) {
            .driver-copy-card {
                position: static;
            }
        }
    </style>

    <!-- DRIVE SECTION: Onboarding page for prospective drivers -->
    <section id="drive" class="driver-stage py-20 px-6 md:px-12 lg:px-16 min-h-screen bg-gradient-to-br from-slate-100 via-white to-rose-50 text-slate-900">
        <div class="max-w-[1380px] mx-auto grid lg:grid-cols-[0.95fr_1.05fr] gap-12 xl:gap-16 items-start relative z-[1]">
            <!-- Informational Column: Highlights benefits of driving with Zuber -->
            <div class="driver-copy-card lg:sticky lg:top-28">
                <div class="driver-pill">
                    <span>Join Our Team</span>
                </div>
                <h2 class="text-5xl md:text-6xl xl:text-7xl font-black mb-8 leading-[0.92]">Drive with<br>Zuber.</h2>
                <div class="space-y-1">
                    <div class="driver-benefit">
                        <div class="driver-benefit-icon"><i class="fas fa-plus"></i></div>
                        <div>
                            <h4 class="font-bold text-2xl text-slate-900">Earnings</h4>
                            <p class="text-slate-500 text-base mt-1.5 max-w-md">Manage your schedule and track your daily revenue through our dashboard.</p>
                        </div>
                    </div>
                    <div class="driver-benefit">
                        <div class="driver-benefit-icon"><i class="fas fa-lock"></i></div>
                        <div>
                            <h4 class="font-bold text-2xl text-slate-900">Safety Support</h4>
                            <p class="text-slate-500 text-base mt-1.5 max-w-md">Access 24/7 support and real-time trip monitoring for your security.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Column: Multi-step registration and KYC process -->
            <div class="driver-form-shell text-black overflow-hidden">
                <!-- STEP 1: Driver login -->
                <div id="driverStep1" class="space-y-7 animate-in fade-in slide-in-from-right-4 duration-500">
                    <div>
                        <h3 class="driver-panel-title text-3xl font-black">Driver Login</h3>
                        <p class="text-slate-500 text-base mt-2">Sign in to your driver account to access your dashboard.</p>
                    </div>
                    <div class="space-y-4">
                        <div class="relative">
                            <label class="driver-label text-[10px] font-black uppercase pl-2 mb-2 block">Gmail Address</label>
                            <input type="text" id="driverEmail" placeholder="anything@gmail.com" pattern=".*@gmail\.com$" title="Email must end with @gmail.com" class="driver-input w-full px-5 py-4 outline-none transition-all font-bold text-[15px] text-slate-900 placeholder-slate-400">
                        </div>
                        <div class="relative">
                            <label class="driver-label text-[10px] font-black uppercase pl-2 mb-2 block">Password</label>
                            <input type="password" id="driverPass" placeholder="********" class="driver-input w-full px-5 py-4 outline-none transition-all font-bold text-[15px] text-slate-900 placeholder-slate-400">
                        </div>
                    </div>
                    <button id="driverLoginBtn" onclick="loginDriver()" class="driver-submit w-full bg-slate-950 text-white font-black text-base hover:bg-slate-800 transition transform active:scale-95 uppercase">Enter Dashboard</button>
                    <p class="text-center text-[11px] text-slate-400 px-4">Need a driver account first? <button type="button" onclick="showApplicationStep()" class="text-primary font-bold hover:underline">Apply now</button></p>
                </div>

                <!-- STEP 2: KYC (Know Your Customer) and personal documentation -->
                <div id="driverStep2" class="hidden space-y-7 animate-in fade-in slide-in-from-right-4 duration-500">
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <h3 class="driver-panel-title text-4xl font-extrabold text-black">KYC Verification</h3>
                            <p class="text-slate-500 mt-2 font-medium italic text-lg">Complete your profile to start driving</p>
                        </div>
                        <button onclick="prevDriverStep()" class="text-slate-400 hover:text-black transition uppercase text-[11px] font-bold tracking-[0.18em] outline-none pt-3">Back</button>
                    </div>

                    <!-- Scrollable area for extensive form fields -->
                    <div class="max-h-[560px] overflow-y-auto no-scrollbar pr-2 pt-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="driver-label text-[10px] font-bold uppercase pl-2 block">First Name</label>
                                <input type="text" id="driverFirstName" placeholder="John" class="driver-input w-full px-5 py-4 outline-none transition-all text-[15px] font-semibold text-slate-800 shadow-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="driver-label text-[10px] font-bold uppercase pl-2 block">Last Name</label>
                                <input type="text" id="driverLastName" placeholder="Doe" class="driver-input w-full px-5 py-4 outline-none transition-all text-[15px] font-semibold text-slate-800 shadow-sm">
                            </div>

                            <div class="space-y-2 relative" id="customGenderDropdown">
                                <label class="driver-label text-[10px] font-bold uppercase pl-2 block">Gender Selection</label>
                                <div onclick="toggleGenderTray()" class="driver-select-trigger w-full px-5 py-4 flex items-center justify-between cursor-pointer transition-all text-[15px] font-semibold shadow-sm">
                                    <span id="selectedGenderLabel" class="text-gray-400">Select Gender</span>
                                    <input type="hidden" id="driverGender" value="">
                                    <i class="fas fa-chevron-down text-[10px] text-gray-300"></i>
                                </div>
                                <div id="genderOptionsTray" class="hidden absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-2xl shadow-lg z-[100] overflow-hidden p-1 opacity-0 translate-y-2 transition-all duration-300">
                                    <div onclick="selectGender('Male')" class="p-4 hover:bg-gray-50 rounded-xl flex items-center justify-between cursor-pointer transition-all">
                                        <span class="font-medium text-gray-600">Male</span>
                                    </div>
                                    <div onclick="selectGender('Female')" class="p-4 hover:bg-gray-50 rounded-xl flex items-center justify-between cursor-pointer transition-all">
                                        <span class="font-medium text-gray-600">Female</span>
                                    </div>
                                    <div onclick="selectGender('Other')" class="p-4 hover:bg-gray-50 rounded-xl flex items-center justify-between cursor-pointer transition-all">
                                        <span class="font-medium text-gray-600">Other</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="driver-label text-[10px] font-bold uppercase pl-2 block">Mobile Number</label>
                                <div class="relative flex items-center">
                                    <span class="absolute left-5 text-[15px] font-black text-black select-none pointer-events-none">+91</span>
                                    <input type="tel" id="driverContact" placeholder="00000 00000" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" class="driver-prefix-input w-full py-4 pr-5 pl-16 outline-none transition-all text-[15px] font-semibold text-slate-800 shadow-sm">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="driver-label text-[10px] font-bold uppercase pl-2 block">Aadhaar / PAN</label>
                                <input type="text" id="driverGovId" placeholder="Enter 12-digit ID" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)" class="driver-input w-full px-5 py-4 outline-none transition-all text-[15px] font-semibold text-slate-800 shadow-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="driver-label text-[10px] font-bold uppercase pl-2 block">Driving License</label>
                                <input type="text" id="driverLicense" placeholder="Enter DL number" class="driver-input w-full px-5 py-4 outline-none transition-all text-[15px] font-semibold text-slate-800 shadow-sm">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="driver-label text-[10px] font-bold uppercase pl-2 block">Residence Address</label>
                                <input type="text" id="driverAddress" placeholder="Full Address" class="driver-input w-full px-5 py-4 outline-none transition-all text-[15px] font-semibold text-slate-800 shadow-sm">
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label class="driver-label text-[10px] font-bold uppercase pl-2 block">Passport Photo</label>
                                <input type="file" id="passPhotoInputDrive" class="hidden" accept="image/*" onchange="updatePhotoLabelDrive(this)">
                                <div onclick="document.getElementById('passPhotoInputDrive').click()" class="driver-upload-box relative w-full p-12 border-2 border-dashed flex flex-col items-center justify-center cursor-pointer transition-all group overflow-hidden">
                                    <div id="photoPreviewDrive" class="absolute inset-0 hidden bg-white">
                                        <img id="previewImgDrive" src="" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <p class="text-white text-[10px] font-bold uppercase tracking-widest bg-black/20 backdrop-blur-md px-4 py-2 rounded-lg">Change Photo</p>
                                        </div>
                                    </div>
                                    <div id="uploadUI" class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-gray-300 mb-3 group-hover:text-black transition-colors">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                        <p id="photoLabelDrive" class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Click to upload photo</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button id="submitDriverApp" onclick="submitApplication()" class="driver-submit w-full bg-slate-950 text-white font-black text-lg hover:bg-slate-800 transition transform active:scale-95 uppercase">Submit Application</button>
                </div>
            </div>
        </div>
    </section>

    <!-- CLIENT-SIDE LOGIC: Multi-step navigation and form handling -->
    <script>
        function showApplicationStep() {
            document.getElementById('driverStep1').classList.add('hidden');
            document.getElementById('driverStep2').classList.remove('hidden');
        }

        function prevDriverStep() {
            document.getElementById('driverStep2').classList.add('hidden');
            document.getElementById('driverStep1').classList.remove('hidden');
        }

        async function loginDriver() {
            const email = document.getElementById('driverEmail').value.trim();
            const pass = document.getElementById('driverPass').value;
            const loginBtn = document.getElementById('driverLoginBtn');

            if(!email || !pass) { alert("Please enter both Gmail and Password to continue."); return; }
            if(!/@gmail\.com$/i.test(email)) { alert("Email must end with @gmail.com."); return; }

            loginBtn.disabled = true;
            loginBtn.innerText = "Signing In...";

            try {
                const formData = new FormData();
                formData.append('email', email);
                formData.append('password', pass);

                const response = await fetch('api/login.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`Server responded with ${response.status}`);
                }

                const res = await response.json();
                if (res.success) {
                    window.location.href = res.redirect || 'index.php';
                    return;
                }

                alert(res.message || "Login failed.");
            } catch (e) {
                console.error(e);
                alert("Login Failed: " + e.message + ". Please try again.");
            }

            loginBtn.disabled = false;
            loginBtn.innerText = "Enter Dashboard";
        }

        function updatePhotoLabelDrive(input) {
            const label = document.getElementById('photoLabelDrive');
            const preview = document.getElementById('photoPreviewDrive');
            const previewImg = document.getElementById('previewImgDrive');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                    label.innerText = "Selected: " + input.files[0].name;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function toggleGenderTray() {
            const tray = document.getElementById('genderOptionsTray');
            if (tray.classList.contains('hidden')) {
                tray.classList.remove('hidden');
                setTimeout(() => {
                    tray.classList.remove('opacity-0', 'translate-y-2');
                }, 10);
            } else {
                tray.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => {
                    tray.classList.add('hidden');
                }, 300);
            }
        }

        function selectGender(gender) {
            const label = document.getElementById('selectedGenderLabel');
            const hiddenInput = document.getElementById('driverGender');
            label.innerText = gender;
            hiddenInput.value = gender;
            label.classList.remove('text-gray-400');
            label.classList.add('text-black');
            toggleGenderTray();
        }

        async function submitApplication() {
            const btn = document.getElementById('submitDriverApp');
            const contactInput = document.getElementById('driverContact');
            const photoInput = document.getElementById('passPhotoInputDrive');
            
            if(contactInput.value.length !== 10) {
                alert("Please enter a valid 10-digit mobile number.");
                return;
            }

            const firstName = document.getElementById('driverFirstName').value;
            const lastName = document.getElementById('driverLastName').value;
            const email = document.getElementById('driverEmail').value;
            const password = document.getElementById('driverPass').value;
            const govId = document.getElementById('driverGovId').value;
            const license = document.getElementById('driverLicense').value;

            if(!firstName || !email || !license) {
                alert("Please fill in all required fields (Name, Email, License).");
                return;
            }

            if(govId.length !== 12) {
                alert("Aadhaar / PAN must be exactly 12 digits.");
                return;
            }

            const formData = new FormData();
            formData.append('firstName', firstName);
            formData.append('lastName', lastName);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('contact', "+91 " + contactInput.value);
            formData.append('govId', govId);
            formData.append('license', license);
            formData.append('address', document.getElementById('driverAddress').value);
            formData.append('gender', document.getElementById('driverGender').value);
            
            if(photoInput.files[0]) {
                formData.append('photo', photoInput.files[0]);
            }

            btn.disabled = true;
            btn.innerText = "Processing...";

            try {
                const response = await fetch('api/apply.php', {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error(`Server responded with ${response.status}`);
                }

                const res = await response.json();
                if(res.success) {
                    alert("Application Submitted Successfully! Your profile is pending approval.");
                    window.location.href = 'login.php';
                } else {
                    alert("Error: " + res.message);
                    btn.disabled = false;
                    btn.innerText = "Submit Application";
                }
            } catch(e) {
                console.error(e);
                alert("Submission Failed: " + e.message + ". Please try again.");
                btn.disabled = false;
                btn.innerText = "Submit Application";
            }
        }
    </script>

<?php 
// Include the global footer
include '../layout/footer.php'; 
?>
