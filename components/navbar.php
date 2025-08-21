<header class="sticky top-0 z-50 bg-white bg-opacity-80 backdrop-blur-md shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <a href="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../index.php' : 'index.php'; ?>" class="flex items-center">
                <img src="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../assets/images/logo.png' : 'assets/images/logo.png'; ?>" alt="KB-TK Islam Al Fath" class="h-12">
                <div class="ml-3 hidden md:block">
                    <h1 class="text-xl font-bold brand-orange">KB-TK Islam Al Fath</h1>
                    <p class="text-xs text-gray-600">Semarang</p>
                </div>
            </a>

            <!-- Navigation - Desktop -->
            <nav class="hidden md:flex items-center space-x-1">
                <a href="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../index.php' : 'index.php'; ?>" class="nav-link px-4 py-2 rounded-md text-gray-700 hover:text-brand-orange transition duration-300">Beranda</a>
                <a href="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../kb.php' : 'kb.php'; ?>" class="nav-link px-4 py-2 rounded-md text-gray-700 hover:text-brand-orange transition duration-300">KB</a>
                <a href="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../tk.php' : 'tk.php'; ?>" class="nav-link px-4 py-2 rounded-md text-gray-700 hover:text-brand-orange transition duration-300">TK</a>
                <a href="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../pages/galeri.php' : 'pages/galeri.php'; ?>" class="nav-link px-4 py-2 rounded-md text-gray-700 hover:text-brand-orange transition duration-300">Galeri</a>
                <a href="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../pages/berita.php' : 'pages/berita.php'; ?>" class="nav-link px-4 py-2 rounded-md text-gray-700 hover:text-brand-orange transition duration-300">Berita</a>
                <a href="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../kontak.php' : 'kontak.php'; ?>" class="nav-link px-4 py-2 rounded-md text-gray-700 hover:text-brand-orange transition duration-300">Kontak</a>
            </nav>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden text-gray-500 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden pb-4">
            <a href="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../index.php' : 'index.php'; ?>" class="block py-2 px-4 text-gray-700 hover:text-brand-orange transition duration-300">Beranda</a>
            <a href="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../kb.php' : 'kb.php'; ?>" class="block py-2 px-4 text-gray-700 hover:text-brand-orange transition duration-300">KB</a>
            <a href="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../tk.php' : 'tk.php'; ?>" class="block py-2 px-4 text-gray-700 hover:text-brand-orange transition duration-300">TK</a>
            <a href="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../pages/galeri.php' : 'pages/galeri.php'; ?>" class="block py-2 px-4 text-gray-700 hover:text-brand-orange transition duration-300">Galeri</a>
            <a href="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../pages/berita.php' : 'pages/berita.php'; ?>" class="block py-2 px-4 text-gray-700 hover:text-brand-orange transition duration-300">Berita</a>
            <a href="<?php echo (strpos($_SERVER['PHP_SELF'], 'pages/') !== false) ? '../kontak.php' : 'kontak.php'; ?>" class="block py-2 px-4 text-gray-700 hover:text-brand-orange transition duration-300">Kontak</a>
        </div>
    </div>
</header>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });

    // Highlight active page
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        const mobileLinks = document.querySelectorAll('#mobile-menu a');
        
        function highlightLink(links) {
            links.forEach(link => {
                const linkPath = link.getAttribute('href');
                if (currentPath.includes(linkPath) && linkPath !== 'index.php') {
                    link.classList.add('text-brand-orange', 'font-medium');
                } else if (currentPath.endsWith('/') || currentPath.endsWith('index.php')) {
                    if (linkPath === 'index.php') {
                        link.classList.add('text-brand-orange', 'font-medium');
                    }
                }
            });
        }
        
        highlightLink(navLinks);
        highlightLink(mobileLinks);
    });
</script>