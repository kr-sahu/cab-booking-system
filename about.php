<?php 
// Include the global header for site-wide navigation
include 'layout/header.php'; 
?>

    <!-- ABOUT US SECTION: Dashboard-structured about page -->
    <main class="min-h-[85vh] py-16 px-6 md:px-12 relative flex items-center">
        <div class="max-w-[1400px] w-full mx-auto grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            <!-- Visual Column: Dashboard style image container -->
            <div class="relative w-full order-2 lg:order-1">
                <div class="absolute inset-0 bg-primary/5 rounded-[2.5rem] transform -translate-x-6 translate-y-6"></div>
                <div class="relative bg-white rounded-[2rem] overflow-hidden shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] border border-slate-100 p-2 h-[500px]">
                    <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2000&auto=format&fit=crop" class="w-full h-full object-cover rounded-[1.5rem]" alt="About Zuber">
                </div>
            </div>
            
            <!-- Content Column: Clean typography and dashboard metric-like cards -->
            <div class="space-y-8 order-1 lg:order-2 relative z-10">
                <div>
                    <div class="inline-block px-4 py-1.5 bg-primary/10 border border-primary/20 rounded-full mb-4">
                        <span class="text-[12px] font-bold text-primary uppercase tracking-widest">Our Mission</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-[1.2] tracking-tight">
                        Redefining the way the world moves.
                    </h2>
                </div>
                
                <p class="text-slate-500 text-lg leading-relaxed max-w-lg">
                    This platform is a comprehensive demonstration of a modern mobility solution, developed to showcase safe, efficient, and reliable transportation management. Focusing on user-centric design and real-time connectivity, it integrates advanced routing and automated booking logic to deliver a premium urban travel experience.
                </p>

                <!-- Core Values: Data-card style presentation -->
                <div class="space-y-4 pt-6">
                    <h4 class="font-extrabold text-sm text-slate-400 uppercase tracking-widest">Our Values</h4>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-4 bg-white border border-slate-200 p-5 rounded-[1.25rem] shadow-[0_4px_12px_rgba(0,0,0,0.03)] hover:border-primary/30 transition-colors">
                            <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center shadow-inner">
                                <i class="fas fa-shield-alt text-lg"></i>
                            </div>
                            <span class="font-bold text-slate-800 text-[15px]">Safety First</span>
                        </div>
                        <div class="flex items-center gap-4 bg-white border border-slate-200 p-5 rounded-[1.25rem] shadow-[0_4px_12px_rgba(0,0,0,0.03)] hover:border-slate-300 transition-colors">
                            <div class="w-12 h-12 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center shadow-inner">
                                <i class="fas fa-bolt text-lg"></i>
                            </div>
                            <span class="font-bold text-slate-800 text-[15px]">Innovation</span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </main>

<?php 
// Include the global footer
include 'layout/footer.php'; 
?>