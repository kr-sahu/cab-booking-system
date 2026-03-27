    <!-- GLOBAL FOOTER: Site-wide footer with branding and legal declaration -->
    <footer class="mt-32 pt-16 border-t border-white/5 text-center px-6 pb-12">
        <p class="text-gray-500 font-bold text-xs uppercase tracking-[0.5em]">&copy; 2026 Zuber. Developed by Project Team<br><span class="mt-2 block">Krushna &bull; Prasanya &bull; Sruti &bull; Trupti</span></p>
    </footer>

    <script>
        // GLOBAL MODAL CONTROLLER: Redirects for rides history if needed
        function openBubble(t) {
            if(t !== 'profile') { 
                window.location.href = 'history.php';
            } else {
                window.location.href = 'account.php';
            }
        }
    </script>
</body>
</html>
