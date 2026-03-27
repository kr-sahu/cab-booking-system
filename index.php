<?php 
include 'layout/header.php'; 
?>

    <!-- Hero -->
    <main class="min-h-[85vh] flex items-center justify-center px-6 py-8 md:px-12 md:py-12 relative overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(96,165,250,0.12),_transparent_30%),radial-gradient(circle_at_bottom_right,_rgba(248,113,113,0.12),_transparent_28%),linear-gradient(180deg,_#f8fbff_0%,_#f8fafc_100%)]">
        <div class="absolute top-20 left-[8%] w-44 h-44 rounded-full bg-blue-200/30 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-16 right-[10%] w-52 h-52 rounded-full bg-rose-200/30 blur-3xl pointer-events-none"></div>

        <div class="max-w-[1400px] w-full mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center relative z-10">
            
            <div class="space-y-8 pr-0 lg:pr-10 relative z-10">
                <div class="inline-flex items-center gap-3 px-4 py-1.5 bg-white/80 border border-primary/20 rounded-full shadow-sm backdrop-blur-sm">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary shadow-[0_0_0_6px_rgba(255,75,75,0.12)]"></span>
                    <span class="text-[12px] font-bold text-primary uppercase tracking-widest">Premium Transport</span>
                </div>
                
                <div class="space-y-5">
                    <h2 class="text-5xl md:text-7xl font-extrabold text-slate-900 leading-[0.96] tracking-[-0.05em]">
                        Move with <br> <span class="text-primary">Style.</span>
                    </h2>
                    <p class="text-slate-500 text-lg md:text-[22px] max-w-xl leading-relaxed">
                        Connect with the city and move in comfort. Reliable trips, professional service, and a seamless journey, all in one place.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3 text-[12px] font-bold uppercase tracking-[0.2em] text-slate-500">
                    <div class="px-4 py-2 rounded-full bg-white/85 border border-slate-200 shadow-sm">24/7 Availability</div>
                    <div class="px-4 py-2 rounded-full bg-white/85 border border-slate-200 shadow-sm">Verified Drivers</div>
                    <div class="px-4 py-2 rounded-full bg-white/85 border border-slate-200 shadow-sm">Smooth Booking</div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 pt-1">
                    <a href="ride.php" class="flex-1 bg-white/92 border border-slate-200 p-5 rounded-[1.4rem] shadow-[0_14px_30px_-18px_rgba(15,23,42,0.18)] hover:shadow-[0_24px_45px_-22px_rgba(255,75,75,0.35)] hover:-translate-y-1 hover:border-primary/30 transition-all group flex items-center gap-4 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                            <i class="fas fa-car text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Passenger</p>
                            <p class="font-bold text-slate-800 text-[15px]">Request a Ride</p>
                        </div>
                        <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-primary group-hover:translate-x-1 transition-all"></i>
                    </a>
                    
                    <a href="driver/apply.php" class="flex-1 bg-white/92 border border-slate-200 p-5 rounded-[1.4rem] shadow-[0_14px_30px_-18px_rgba(15,23,42,0.18)] hover:shadow-[0_24px_45px_-22px_rgba(15,23,42,0.3)] hover:-translate-y-1 hover:border-slate-300 transition-all group flex items-center gap-4 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-600 group-hover:bg-slate-800 group-hover:text-white transition-colors">
                            <i class="fas fa-id-card text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Partner</p>
                            <p class="font-bold text-slate-800 text-[15px]">Become a Driver</p>
                        </div>
                        <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-slate-800 group-hover:translate-x-1 transition-all"></i>
                    </a>
                </div>
            </div>

            <div class="relative z-10 w-full h-full flex items-center justify-center lg:justify-end">
                <div class="relative w-full max-w-[540px]">
                    <div class="absolute -inset-3 bg-gradient-to-br from-white/70 to-primary/5 rounded-[2.7rem] blur-sm"></div>
                    <div class="absolute inset-0 bg-primary/6 rounded-[2.5rem] transform translate-y-5 translate-x-5"></div>
                    <div class="relative bg-white rounded-[2rem] overflow-hidden shadow-[0_24px_50px_-18px_rgba(15,23,42,0.2)] border border-slate-100 p-2">
                        <img src="assets/hero_ride.jpg" alt="Ride with Style" class="w-full h-[520px] object-cover rounded-[1.5rem]">
                        <div class="absolute top-6 right-6 px-4 py-2 rounded-full bg-slate-950/78 text-white text-[11px] font-bold tracking-[0.18em] uppercase backdrop-blur-sm border border-white/10">
                            Safe • Premium
                        </div>
                        <div class="absolute bottom-6 left-6 right-6 p-6 bg-white/95 backdrop-blur-sm shadow-lg rounded-[1.25rem] border border-slate-50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <p class="text-slate-800 font-bold text-sm">Travel in Style</p>
                                    <p class="text-slate-500 text-[12px] font-medium leading-tight mt-0.5">Focusing on your comfort and safety every mile.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </main>

<?php 
include 'layout/footer.php'; 
?>
