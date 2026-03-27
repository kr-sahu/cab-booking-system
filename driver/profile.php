<?php
include 'inc/header.php';
include 'inc/sidebar.php';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- MAIN CONTENT AREA -->
<main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F8FAFC]">
    
    <!-- HEADER -->
    <div class="px-8 py-6 bg-white border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-gray-800">Profile Management</h2>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Manage your personal and KYC documentation</p>
        </div>
        <div class="flex items-center gap-3">
             <span class="bg-success/10 text-success text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-success/20">Verified Partner</span>
        </div>
    </div>

    <!-- SCROLLABLE CONTENT -->
    <div class="flex-1 overflow-y-auto p-4 lg:p-12 flex items-start justify-center">
        
        <!-- SINGLE MASTER PROFILE CARD - HORIZONTAL -->
        <div class="w-full max-w-[900px] bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-500 flex flex-col md:flex-row">
            
            <!-- LEFT SECTION: IDENTITY -->
            <div class="md:w-1/3 p-8 lg:p-12 text-center bg-slate-50 border-r border-gray-100 flex flex-col items-center justify-center relative">
                <div class="relative inline-block group mb-6">
                    <div class="w-32 h-32 rounded-[2.5rem] overflow-hidden border-4 border-white shadow-2xl relative transition-transform group-hover:scale-105">
                        <img id="profileImageDisplay" src="<?= !empty($driver['profile_image']) ? '../' . $driver['profile_image'] : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $driverId ?>" class="w-full h-full object-cover">
                        <label for="profileImageInput" id="cameraLabel" class="absolute inset-0 bg-black/40 opacity-0 transition-all flex items-center justify-center text-white cursor-pointer pointer-events-none">
                            <i class="fas fa-camera text-2xl"></i>
                        </label>
                        <input type="file" id="profileImageInput" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                </div>

                <div id="displayIdentity">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight"><?= $driver['name'] ?></h3>
                    <p class="font-bold text-slate-400 text-xs mt-1 italic">Member since <?= date('M Y', strtotime($driver['created_at'])) ?></p>
                </div>
            </div>

            <!-- RIGHT SECTION: DATA & ACTIONS -->
            <div class="flex-1 p-8 lg:p-12">
                <form id="profileForm" onsubmit="handleProfileUpdate(event)" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NAME (Editable) -->
                        <div class="space-y-1.5">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Full Name</p>
                            <div class="relative">
                                <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                                <input type="text" name="name" value="<?= $driver['name'] ?>" required readonly
                                    class="w-full pl-10 pr-4 py-4 bg-transparent rounded-2xl border border-transparent font-bold text-slate-700 text-[13px] transition-all outline-none">
                            </div>
                        </div>

                        <!-- EMAIL -->
                        <div class="space-y-1.5">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Email Address</p>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                                <input type="email" name="email" value="<?= $driver['email'] ?>" required readonly
                                    class="w-full pl-10 pr-4 py-4 bg-transparent rounded-2xl border border-transparent font-bold text-slate-700 text-[13px] transition-all outline-none">
                            </div>
                        </div>

                        <!-- MOBILE -->
                        <div class="space-y-1.5 flex flex-col">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Mobile Number</p>
                            <div class="relative flex items-center group/field">
                                <div id="mobileContainer" class="w-full flex items-center bg-transparent rounded-2xl border border-transparent transition-all overflow-hidden">
                                     <div class="pl-4 pr-2 py-4 flex items-center gap-2 border-r border-transparent" id="prefixContainer">
                                         <i class="fas fa-phone text-slate-300 text-xs"></i>
                                         <span class="font-black text-slate-400 text-[13px] tracking-tight">+91</span>
                                     </div>
                                     <input type="text" name="contact" id="contactInput" maxlength="10" placeholder="0000000000"
                                        value="<?= substr($driver['contact'] ?? '', -10) ?>" required readonly
                                        class="flex-1 pr-4 py-4 bg-transparent font-bold text-slate-700 text-[13px] transition-all outline-none">
                                </div>
                            </div>
                        </div>

                        <!-- LICENSE (Read-only) -->
                        <div class="space-y-1.5 opacity-80">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Driving License</p>
                            <div class="flex items-center gap-3 p-4 bg-slate-50/50 rounded-2xl border border-slate-100">
                               <i class="fas fa-id-card text-slate-300 text-sm"></i>
                               <p class="font-bold text-slate-500 text-[13px] uppercase"><?= $driver['license_no'] ?></p>
                            </div>
                        </div>

                        <!-- AADHAAR (Read-only) -->
                        <div class="space-y-1.5">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Aadhaar Status</p>
                            <div class="flex items-center gap-3 p-4 bg-slate-50/50 rounded-2xl border border-slate-100">
                               <i class="fas fa-fingerprint text-success text-sm"></i>
                               <p class="font-bold text-success text-[13px]">Identity Verified</p>
                            </div>
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <div class="pt-6 border-t border-gray-50 flex gap-4" id="actionArea">
                        <button type="button" onclick="toggleEditMode()" id="editBtn" class="flex-1 py-4 bg-blue-600 text-white rounded-2xl font-black text-[12px] uppercase tracking-widest shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all transform active:scale-[0.98] flex items-center justify-center gap-3">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    </div>

                    <div class="pt-6 border-t border-gray-50 hidden gap-4" id="saveArea">
                        <button type="button" onclick="toggleEditMode()" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 py-4 bg-emerald-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-emerald-500/20 hover:bg-emerald-700 transition-all transform active:scale-[0.98]">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    let isEditing = false;
    
    function toggleEditMode() {
        isEditing = !isEditing;
        const form = document.getElementById('profileForm');
        const inputs = form.querySelectorAll('input:not([type="file"])');
        
        inputs.forEach(input => {
            if(input.name !== 'email') { 
                input.readOnly = !isEditing;
                
                // For regular inputs
                if(input.id !== 'contactInput') {
                    input.classList.toggle('bg-slate-50/50', isEditing);
                    input.classList.toggle('border-slate-100', isEditing);
                    input.classList.toggle('bg-transparent', !isEditing);
                    input.classList.toggle('border-transparent', !isEditing);
                    if(isEditing) input.classList.add('focus:border-blue-500');
                    else input.classList.remove('focus:border-blue-500');
                } else {
                    // For mobile container
                    const container = document.getElementById('mobileContainer');
                    const prefix = document.getElementById('prefixContainer');
                    container.classList.toggle('bg-slate-50/50', isEditing);
                    container.classList.toggle('border-slate-100', isEditing);
                    container.classList.toggle('bg-transparent', !isEditing);
                    container.classList.toggle('border-transparent', !isEditing);
                    prefix.classList.toggle('border-slate-200/50', isEditing);
                    if(isEditing) container.classList.add('ring-2', 'ring-blue-500/10', 'border-blue-500');
                    else container.classList.remove('ring-2', 'ring-blue-500/10', 'border-blue-500');
                }
            }
        });

        document.getElementById('actionArea').classList.toggle('hidden', isEditing);
        document.getElementById('saveArea').classList.toggle('hidden', !isEditing);
        document.getElementById('saveArea').classList.add('flex');
        
        const cameraLabel = document.getElementById('cameraLabel');
        if(isEditing) {
            cameraLabel.classList.remove('pointer-events-none');
            cameraLabel.classList.add('group-hover:opacity-100');
        } else {
            cameraLabel.classList.add('pointer-events-none');
            cameraLabel.classList.remove('group-hover:opacity-100', 'opacity-100');
        }
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileImageDisplay').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    async function handleProfileUpdate(e) {
        e.preventDefault();
        const contactRaw = document.getElementById('contactInput').value;
        const formData = new FormData(e.target);
        
        // Re-format contact with fixed prefix
        formData.set('contact', '+91' + contactRaw);

        const imgInput = document.getElementById('profileImageInput');
        if(imgInput.files[0]) {
            formData.append('profile_image', imgInput.files[0]);
        }

        try {
            // Show loading
            const saveBtn = e.target.querySelector('button[type="submit"]');
            saveBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Saving...';
            saveBtn.disabled = true;

            const response = await fetch('api/update_profile.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            if(result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated',
                    text: 'Your changes have been saved successfully!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Something went wrong'
            });
            toggleEditMode();
        }
    }

    window.onload = () => {
        updateStatusUI();
    };
</script>
</body>
</html>
