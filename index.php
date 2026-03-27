<?php 
// Include the global header and navigation bar
include 'layout/header.php'; 
?>

    <!-- HERO SECTION: Clean dashboard-like layout -->
    <main class="min-h-[85vh] flex items-center justify-center p-6 md:p-12 relative">
        <div class="max-w-[1400px] w-full mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            <!-- Left Column: Typography and actions -->
            <div class="space-y-8 pr-0 lg:pr-10 relative z-10">
                <div class="inline-block px-4 py-1.5 bg-primary/10 border border-primary/20 rounded-full mb-2">
                    <span class="text-[12px] font-bold text-primary uppercase tracking-widest">Premium Transport</span>
                </div>
                
                <h2 class="text-5xl md:text-7xl font-extrabold text-slate-900 leading-[1.1] tracking-tight">
                    Move with <br> <span class="text-primary">Style.</span>
                </h2>
                <p class="text-slate-500 text-lg md:text-xl max-w-lg leading-relaxed">
                    Connect with the city and move in comfort. Reliable trips, professional service, and a seamless journey—all in one place.
                </p>
                
                <!-- Action Cards -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <a href="ride.php" class="flex-1 bg-white border border-slate-200 p-5 rounded-[1.25rem] shadow-[0_4px_12px_rgba(0,0,0,0.03)] hover:shadow-md hover:border-primary/30 transition-all group flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                            <i class="fas fa-car text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Passenger</p>
                            <p class="font-bold text-slate-800 text-[15px]">Request a Ride</p>
                        </div>
                        <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-primary transition-colors"></i>
                    </a>
                    
                    <a href="driver/apply.php" class="flex-1 bg-white border border-slate-200 p-5 rounded-[1.25rem] shadow-[0_4px_12px_rgba(0,0,0,0.03)] hover:shadow-md hover:border-slate-300 transition-all group flex items-center gap-4">
                        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 group-hover:bg-slate-800 group-hover:text-white transition-colors">
                            <i class="fas fa-id-card text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Partner</p>
                            <p class="font-bold text-slate-800 text-[15px]">Become a Driver</p>
                        </div>
                        <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-slate-800 transition-colors"></i>
                    </a>
                </div>
            </div>

            <!-- Right Column: Visual Container -->
            <div class="relative z-10 w-full h-full flex items-center justify-center lg:justify-end">
                <div class="relative w-full max-w-[500px]">
                    <div class="absolute inset-0 bg-primary/5 rounded-[2.5rem] transform translate-y-6 translate-x-6"></div>
                    <div class="relative bg-white rounded-[2rem] overflow-hidden shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] border border-slate-100 p-2">
                        <img src="assets/hero_ride.jpg" alt="Ride with Style" class="w-full h-[500px] object-cover rounded-[1.5rem]">
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
// Include the global footer with site links and copyright
include 'layout/footer.php'; 
?>
