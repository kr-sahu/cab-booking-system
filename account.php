<?php 
include 'layout/header.php'; 

// Guard access for signed-in users only.
if (!$isLoggedIn) {
    header("Location: auth.php?mode=login");
    exit();
}

// Load the latest profile details and summary stats.
$u_id = $_SESSION['user_id'];
$u_res = $conn->query("SELECT * FROM users WHERE id = $u_id");
$user = $u_res->fetch_assoc();

$trips_res = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE user_id = $u_id");
$trips_count = $trips_res ? $trips_res->fetch_assoc()['total'] : 0;
?>

<div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(96,165,250,0.12),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(248,113,113,0.12),_transparent_28%),linear-gradient(180deg,_#f8fbff_0%,_#f8fafc_100%)] py-12 px-4 md:px-8">
    <div class="max-w-7xl mx-auto">
        <a href="index.php" class="inline-flex items-center gap-2 text-slate-400 hover:text-slate-900 font-bold text-xs uppercase tracking-[0.22em] mb-8 transition-colors group">
            <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Back
        </a>

        <div class="grid grid-cols-1 xl:grid-cols-[360px_minmax(0,1fr)] gap-8 items-start">
            <div class="space-y-6">
                <div class="bg-gradient-to-br from-slate-950 via-slate-900 to-blue-900 rounded-[2rem] shadow-[0_24px_60px_-28px_rgba(15,23,42,0.8)] border border-slate-800 flex flex-col items-center pt-12 pb-10 px-8 relative text-white">
                    <div class="absolute -right-10 -top-10 w-36 h-36 rounded-full bg-white/10 blur-2xl"></div>
                    <div class="absolute -left-8 bottom-4 w-24 h-24 rounded-full bg-sky-400/20 blur-2xl"></div>
                    
                    <div class="relative mb-6 z-10 group" id="avatarMenuContainer">
                        <div class="w-28 h-28 rounded-full border border-white/20 shadow-xl overflow-hidden relative bg-white/10">
                            <img id="previewAvatar" src="<?= $userImage ?>" class="w-full h-full object-cover rounded-full">

                            <button type="button" onclick="toggleAvatarOptions(event)" class="absolute inset-0 rounded-full bg-slate-950/0 hover:bg-slate-950/18 focus:bg-slate-950/18 transition-all duration-200 flex items-center justify-center opacity-0 group-hover:opacity-100 group-focus-within:opacity-100">
                                <span class="w-12 h-12 text-white rounded-full flex items-center justify-center bg-white/10 backdrop-blur-sm">
                                    <i class="fas fa-camera text-[16px]"></i>
                                </span>
                            </button>
                        </div>

                        <div id="avatarOptions" class="hidden absolute left-1/2 -translate-x-1/2 top-full mt-3 w-52 bg-white rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.18)] border border-slate-100 z-50 overflow-hidden scale-95 opacity-0 transition-all duration-200 md:left-full md:top-1/2 md:ml-4 md:mt-0 md:translate-x-0 md:-translate-y-1/2">
                            <div onclick="document.getElementById('avatarInput').click()" class="px-5 py-3.5 hover:bg-slate-50 cursor-pointer text-[11px] font-bold text-slate-700 flex items-center gap-3 border-b border-slate-100 whitespace-nowrap">
                                <i class="fas fa-images text-blue-500"></i> Change Picture
                            </div>
                            <div onclick="removeProfilePicture()" class="px-5 py-3.5 hover:bg-red-50 cursor-pointer text-[11px] font-bold text-red-500 flex items-center gap-3 whitespace-nowrap">
                                <i class="fas fa-trash-alt"></i> Remove Picture
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-8 z-10">
                        <h2 class="text-3xl font-extrabold text-white mb-1"><?= htmlspecialchars($user['fullname']) ?></h2>
                        <p class="text-sm font-medium text-slate-300 mb-4 break-all"><?= htmlspecialchars($user['email']) ?></p>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 text-blue-100 rounded-full text-[10px] font-black uppercase tracking-[0.22em] border border-white/10">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            Premium Traveler
                        </div>
                    </div>

                    <div class="w-full border-t border-white/10 pt-8 z-10">
                        <div class="text-center">
                            <p class="text-3xl font-black text-white"><?= $trips_count ?></p>
                            <p class="text-[10px] font-black text-blue-100/75 uppercase tracking-[0.22em] mt-1">Total Trips</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/90 backdrop-blur rounded-[2rem] border border-white shadow-[0_16px_50px_-28px_rgba(15,23,42,0.2)] p-6">
                    <p class="text-[11px] font-black uppercase tracking-[0.25em] text-slate-400">Profile Notes</p>
                    <div class="mt-4 space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0"><i class="fas fa-shield-heart"></i></div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">Keep your account verified</h3>
                                <p class="text-sm text-slate-500 mt-1">Use your current password to confirm changes before saving your profile.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0"><i class="fas fa-mobile-screen-button"></i></div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">Travel updates use this number</h3>
                                <p class="text-sm text-slate-500 mt-1">Your ride alerts and driver contact updates will use the number saved here.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-white/90 backdrop-blur rounded-[2rem] shadow-[0_16px_50px_-28px_rgba(15,23,42,0.18)] border border-white p-8 md:p-10">
                    <div class="mb-8">
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-[0.25em] text-slate-400">Account Center</p>
                            <h3 class="text-3xl font-extrabold text-slate-950 mt-3">Personal Details</h3>
                            <p class="text-slate-500 mt-3 max-w-2xl">Refresh your travel profile, contact details, and security settings from one focused workspace.</p>
                        </div>
                    </div>
                    
                    <form id="updateAccountForm" enctype="multipart/form-data" class="space-y-8">
                        <input type="file" id="avatarInput" name="avatar" class="hidden" accept="image/*" onchange="previewFile(this)">
                        
                        <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50/80 p-6 md:p-7">
                            <div class="flex items-center justify-between gap-4 mb-6">
                                <div>
                                    <p class="text-[11px] font-black uppercase tracking-[0.25em] text-slate-400">Identity</p>
                                    <h4 class="text-lg font-black text-slate-950 mt-2">Basic Information</h4>
                                </div>
                                <div class="hidden md:flex items-center gap-2 px-3 py-2 rounded-full bg-white border border-slate-200 text-[10px] font-black uppercase tracking-[0.18em] text-slate-500">
                                    <i class="fas fa-user-pen text-blue-500"></i>
                                    Editable
                                </div>
                            </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-500 uppercase tracking-[0.22em] flex items-center gap-2">Full Name</label>
                                <div class="relative group">
                                    <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors"><i class="fas fa-user text-[13px]"></i></div>
                                    <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" 
                                        class="w-full h-[58px] bg-white border border-slate-200 rounded-2xl pl-12 pr-6 text-sm font-bold text-slate-800 outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-500 uppercase tracking-[0.22em]">Gender</label>
                                <div class="relative" id="genderDropdown">
                                    <input type="hidden" name="gender" id="genderInput" value="<?= htmlspecialchars($user['gender'] ?? '') ?>">
                                    <div onclick="toggleDropdown()" class="w-full h-[58px] bg-white border border-slate-200 rounded-2xl px-5 text-sm font-bold text-slate-800 cursor-pointer flex justify-between items-center hover:border-blue-400 transition-all" id="genderTrigger">
                                        <div class="flex items-center gap-4">
                                            <i class="fas fa-venus-mars text-slate-300 text-[13px]"></i>
                                            <span class="<?= empty($user['gender']) ? 'text-slate-400' : '' ?>"><?= !empty($user['gender']) ? htmlspecialchars($user['gender']) : 'Select Gender' ?></span>
                                        </div>
                                        <i class="fas fa-chevron-down text-slate-300 text-[10px] transition-transform duration-300"></i>
                                    </div>
                                    <div class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden opacity-0 invisible translate-y-[-10px] transition-all duration-300" id="genderPanel">
                                        <div onclick="selectOption('Male')" class="px-5 py-3 hover:bg-slate-50 cursor-pointer text-sm font-bold border-b border-slate-100 transition-colors <?= ($user['gender'] == 'Male') ? 'text-blue-600 bg-blue-50/30' : 'text-slate-600' ?>">Male</div>
                                        <div onclick="selectOption('Female')" class="px-5 py-3 hover:bg-slate-50 cursor-pointer text-sm font-bold border-b border-slate-100 transition-colors <?= ($user['gender'] == 'Female') ? 'text-blue-600 bg-blue-50/30' : 'text-slate-600' ?>">Female</div>
                                        <div onclick="selectOption('Other')" class="px-5 py-3 hover:bg-slate-50 cursor-pointer text-sm font-bold transition-colors <?= ($user['gender'] == 'Other') ? 'text-blue-600 bg-blue-50/30' : 'text-slate-600' ?>">Other</div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-500 uppercase tracking-[0.22em]">Phone Number</label>
                                <div class="relative group flex items-center h-[58px] bg-white border border-slate-200 rounded-2xl px-5 focus-within:ring-4 focus-within:ring-blue-500/10 focus-within:border-blue-500 transition-all">
                                    <div class="text-slate-300 group-focus-within:text-slate-900 transition-all mr-3"><i class="fas fa-phone text-[13px]"></i></div>
                                    <span class="text-sm font-black text-slate-500 mr-3 tracking-tighter">+91</span>
                                    <div class="w-[1px] h-5 bg-slate-200 mr-3"></div>
                                    <?php 
                                        $cleanPhone = str_replace('+91 ', '', $user['phone'] ?? '');
                                        $cleanPhone = preg_replace('/\D/', '', $cleanPhone);
                                    ?>
                                    <input type="tel" id="numericPhone" value="<?= htmlspecialchars($cleanPhone) ?>" placeholder="00000 00000"
                                        class="flex-1 bg-transparent border-none outline-none text-sm font-bold text-slate-800 placeholder:text-slate-300 py-4">
                                    <input type="hidden" name="phone" id="fullPhone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-500 uppercase tracking-[0.22em]">Email Address</label>
                                <div class="relative group">
                                    <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors"><i class="fas fa-envelope text-[13px]"></i></div>
                                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" 
                                        class="w-full h-[58px] bg-white border border-slate-200 rounded-2xl pl-12 pr-6 text-sm font-bold text-slate-800 outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                                </div>
                            </div>
                        </div>
                        </div>

                        <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50/80 p-6 md:p-7">
                            <div class="mb-6">
                                <p class="text-[11px] font-black uppercase tracking-[0.25em] text-slate-400">Security</p>
                                <h4 class="text-lg font-black text-slate-950 mt-2">Password Confirmation</h4>
                            </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-500 uppercase tracking-[0.22em]">Current Password</label>
                                <div class="relative group">
                                    <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors"><i class="fas fa-lock text-[13px]"></i></div>
                                    <input type="password" name="current_password" required placeholder="Enter to confirm changes"
                                        class="w-full h-[58px] bg-white border border-slate-200 rounded-2xl pl-12 pr-14 text-sm font-bold text-slate-800 outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                                    <button type="button" onclick="togglePass(this)" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-900 transition-colors">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-500 uppercase tracking-[0.22em]">New Password</label>
                                <div class="relative group">
                                    <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-slate-900 transition-colors"><i class="fas fa-shield-halved text-[13px]"></i></div>
                                    <input type="password" name="new_password" placeholder="Min 8 characters"
                                        class="w-full h-[58px] bg-white border border-slate-200 rounded-2xl pl-12 pr-14 text-sm font-bold text-slate-800 outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                                    <button type="button" onclick="togglePass(this)" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-900 transition-colors">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>

                        <div class="pt-2 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <p class="text-sm text-slate-500">Your changes are applied only after password confirmation.</p>
                            <button type="submit" class="w-full md:w-auto md:min-w-[280px] bg-slate-950 text-white h-[58px] rounded-2xl font-black text-sm uppercase tracking-[0.22em] shadow-[0_18px_40px_-18px_rgba(15,23,42,0.7)] hover:bg-blue-700 transition-all transform active:scale-[0.98] flex items-center justify-center gap-3 px-8">
                                <i class="fas fa-save"></i> Save Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #genderPanel.open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
</style>

<script>
    // Avatar actions
    function previewFile(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewAvatar').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleAvatarOptions(e) {
        e.stopPropagation();
        const panel = document.getElementById('avatarOptions');
        panel.classList.toggle('hidden');
        setTimeout(() => {
            panel.classList.toggle('scale-95');
            panel.classList.toggle('opacity-0');
        }, 10);
    }

    async function removeProfilePicture() {
        if(!confirm("Are you sure you want to remove your profile picture?")) return;
        try {
            const r = await fetch('api/remove_profile.php');
            const d = await r.json();
            if(d.success) {
                document.getElementById('previewAvatar').src = d.default_image;
                alert(d.message);
                window.location.reload();
            } else alert(d.message);
        } catch(e) { console.error(e); }
    }

    // Gender dropdown
    function toggleDropdown() {
        const panel = document.getElementById('genderPanel');
        const chevron = document.querySelector('#genderTrigger i');
        panel.classList.toggle('open');
        chevron.style.transform = panel.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0deg)';
    }

    function selectOption(val) {
        document.getElementById('genderInput').value = val;
        const display = document.querySelector('#genderTrigger span');
        display.innerText = val;
        display.classList.remove('text-slate-400');
        
        toggleDropdown();
    }

    window.addEventListener('click', (e) => {
        if (document.getElementById('genderDropdown') && !document.getElementById('genderDropdown').contains(e.target)) {
            document.getElementById('genderPanel').classList.remove('open');
            document.querySelector('#genderTrigger i').style.transform = 'rotate(0deg)';
        }

        if (document.getElementById('avatarMenuContainer') && !document.getElementById('avatarMenuContainer').contains(e.target)) {
            const panel = document.getElementById('avatarOptions');
            panel.classList.add('hidden', 'scale-95', 'opacity-0');
        }
    });

    // Password visibility
    function togglePass(btn) {
        const input = btn.parentElement.querySelector('input');
        const icon = btn.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Phone formatting sync
    document.addEventListener('DOMContentLoaded', () => {
        const numInput = document.getElementById('numericPhone');
        const fullInput = document.getElementById('fullPhone');
        
        if (numInput) {
            numInput.addEventListener('input', function() {
                let numbers = this.value.replace(/\D/g, '').slice(0, 10);
                this.value = numbers;
                
                fullInput.value = numbers ? '+91 ' + numbers : '';
            });
        }
    });

    // Profile update submission
    document.getElementById('updateAccountForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerText;

        btn.disabled = true;
        btn.innerText = "Processing...";

        try {
            const response = await fetch('api/update_account.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if(result.success) {
                alert(result.message);
                window.location.reload();
            } else {
                alert(result.message);
                btn.disabled = false;
                btn.innerText = originalText;
            }
        } catch (err) {
            console.error(err);
            alert("An error occurred. Please try again.");
            btn.disabled = false;
            btn.innerText = originalText;
        }
    });
</script>

<?php include 'layout/footer.php'; ?>
