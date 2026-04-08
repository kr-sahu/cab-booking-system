<?php
include '../layout/header.php';
?>

<style>
    .driver-login-stage {
        position: relative;
        overflow: hidden;
    }

    .driver-login-stage::before {
        content: '';
        position: absolute;
        top: 80px;
        left: 4%;
        width: 320px;
        height: 320px;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.11) 0%, rgba(59, 130, 246, 0) 72%);
        pointer-events: none;
    }

    .driver-login-stage::after {
        content: '';
        position: absolute;
        right: -110px;
        bottom: -110px;
        width: 400px;
        height: 400px;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(244, 63, 94, 0.09) 0%, rgba(244, 63, 94, 0) 72%);
        pointer-events: none;
    }

    .driver-login-copy {
        position: relative;
        padding: 2rem;
        border-radius: 2.2rem;
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(226, 232, 240, 0.82);
        box-shadow: 0 18px 40px rgba(148, 163, 184, 0.12);
        backdrop-filter: blur(14px);
    }

    .driver-login-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.55rem 0.9rem;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.04);
        border: 1px solid rgba(148, 163, 184, 0.22);
        margin-bottom: 1.35rem;
    }

    .driver-login-pill span {
        color: #0f172a;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.24em;
    }

    .driver-login-benefit {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 0.85rem 0;
    }

    .driver-login-benefit + .driver-login-benefit {
        border-top: 1px solid rgba(226, 232, 240, 0.78);
    }

    .driver-login-icon {
        width: 58px;
        height: 58px;
        border-radius: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(226, 232, 240, 0.92);
        box-shadow: 0 12px 25px rgba(148, 163, 184, 0.15);
        color: #ef4444;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .driver-login-shell {
        position: relative;
        border-radius: 2.5rem;
        padding: 1.6rem;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(226, 232, 240, 0.92);
        box-shadow: 0 28px 60px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(14px);
    }

    .driver-login-shell::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.14), rgba(244, 63, 94, 0.08));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    .driver-login-input {
        min-height: 62px;
        border-radius: 1.35rem;
        border: 1px solid #e2e8f0;
        background: linear-gradient(180deg, #f8fbff 0%, #f8fafc 100%);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
    }

    .driver-login-input:focus {
        border-color: #94a3b8;
        box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.05);
        background: #fff;
    }

    .driver-login-submit {
        border-radius: 1.55rem;
        padding-top: 1rem;
        padding-bottom: 1rem;
        letter-spacing: 0.18em;
        box-shadow: 0 20px 34px rgba(15, 23, 42, 0.14);
    }

    @media (max-width: 1024px) {
        .driver-login-copy {
            position: static;
        }
    }
</style>

<!-- Driver login -->
<section class="driver-login-stage py-14 px-6 md:px-10 lg:px-14 min-h-screen bg-gradient-to-br from-slate-100 via-white to-rose-50 text-slate-900">
    <div class="max-w-[1320px] mx-auto grid lg:grid-cols-[0.88fr_1fr] gap-8 xl:gap-10 items-start relative z-[1]">
        <div class="driver-login-copy lg:sticky lg:top-28">
            <div class="driver-login-pill">
                <span>Driver Access</span>
            </div>
            <h2 class="text-5xl md:text-6xl xl:text-7xl font-black mb-8 leading-[0.92] tracking-[-0.06em]">Welcome back,<br>Partner.</h2>
            <div class="space-y-1">
                <div class="driver-login-benefit">
                    <div class="driver-login-icon"><i class="fas fa-route"></i></div>
                    <div>
                        <h4 class="font-bold text-2xl text-slate-900">Trip Control</h4>
                        <p class="text-slate-500 text-base mt-1.5 max-w-md">Accept rides, manage live trips, and keep your availability updated in one place.</p>
                    </div>
                </div>
                <div class="driver-login-benefit">
                    <div class="driver-login-icon"><i class="fas fa-wallet"></i></div>
                    <div>
                        <h4 class="font-bold text-2xl text-slate-900">Earnings View</h4>
                        <p class="text-slate-500 text-base mt-1.5 max-w-md">Track completed rides and daily earnings right from your driver dashboard.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="driver-login-shell text-black">
            <div class="space-y-5">
                <div>
                    <h3 class="text-3xl font-black tracking-[-0.05em]">Driver Login</h3>
                    <p class="text-slate-500 text-base mt-2">Use your approved driver account credentials to enter the partner dashboard.</p>
                </div>

                <div id="driverLoginMessage" class="hidden rounded-2xl border px-4 py-3 text-sm font-semibold"></div>

                <form id="driverLoginForm" class="space-y-4" novalidate>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.12em] text-slate-500 pl-2 block">Gmail Address</label>
                        <input type="email" id="driverLoginEmail" placeholder="anything@gmail.com" class="driver-login-input w-full px-5 py-4 outline-none transition-all font-bold text-[15px] text-slate-900 placeholder-slate-400" autocomplete="email">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.12em] text-slate-500 pl-2 block">Password</label>
                        <input type="password" id="driverLoginPassword" placeholder="Enter your password" class="driver-login-input w-full px-5 py-4 outline-none transition-all font-bold text-[15px] text-slate-900 placeholder-slate-400" autocomplete="current-password">
                    </div>

                    <button id="driverLoginBtn" type="submit" class="driver-login-submit w-full bg-slate-950 text-white font-black text-base hover:bg-slate-800 transition transform active:scale-95 uppercase">
                        Login to Dashboard
                    </button>
                </form>

                <p class="text-center text-[12px] text-slate-400 px-4">
                    New to driving with Zuber?
                    <a href="apply.php" class="text-primary font-bold hover:underline">Apply here</a>
                </p>
            </div>
        </div>
    </div>
</section>

<script>
    // Shared form elements
    const loginForm = document.getElementById('driverLoginForm');
    const loginBtn = document.getElementById('driverLoginBtn');
    const loginMessage = document.getElementById('driverLoginMessage');

    // Inline status messaging
    function setLoginMessage(message, type = 'error') {
        // Shows validation or API feedback inside the login card.
        loginMessage.textContent = message;
        loginMessage.className = 'rounded-2xl border px-4 py-3 text-sm font-semibold';

        if (type === 'success') {
            loginMessage.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-700');
        } else {
            loginMessage.classList.add('bg-rose-50', 'border-rose-200', 'text-rose-700');
        }

        loginMessage.classList.remove('hidden');
    }

    // Login submission
    loginForm.addEventListener('submit', async function (event) {
        // Validates credentials locally, then posts them to the driver login API.
        event.preventDefault();

        const email = document.getElementById('driverLoginEmail').value.trim();
        const password = document.getElementById('driverLoginPassword').value;

        if (!/@gmail\.com$/i.test(email)) {
            setLoginMessage('Email must end with @gmail.com.');
            return;
        }

        if (password.length < 6 || password.length > 32) {
            setLoginMessage('Password length must be between 6 and 32 characters.');
            return;
        }

        loginBtn.disabled = true;
        loginBtn.textContent = 'Checking...';
        loginMessage.classList.add('hidden');

        const formData = new FormData();
        formData.append('email', email);
        formData.append('password', password);

        try {
            const response = await fetch('api/login.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Unable to reach the login service.');
            }

            const result = await response.json();

            if (!result.success) {
                setLoginMessage(result.message || 'Login failed. Please try again.');
                loginBtn.disabled = false;
                loginBtn.textContent = 'Login to Dashboard';
                return;
            }

            setLoginMessage(result.message || 'Login successful.', 'success');
            window.location.href = result.redirect || 'index.php';
        } catch (error) {
            setLoginMessage(error.message || 'Login failed. Please try again.');
            loginBtn.disabled = false;
            loginBtn.textContent = 'Login to Dashboard';
        }
    });
</script>

<?php
include '../layout/footer.php';
?>
