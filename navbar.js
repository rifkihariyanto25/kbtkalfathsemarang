// Navbar Component for TK-KB Al-Fath

function createNavbar() {
    // Create the navbar HTML structure
    const navbarHTML = `
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a href="index.html" class="flex items-center space-x-3">
                <img src="assets/WhatsApp Image 2025-07-30 at 09.47.34_ce7ed10a 1.png" alt="Logo Al-Fath"
                    class="h-12 w-12 rounded-full transform hover:rotate-12 transition-transform duration-300">
                <span class="text-xl font-bold text-gray-800">KB TK Al-Fath</span>
            </a>
            <nav class="hidden md:flex space-x-8 items-center">
                <a href="index.html#beranda"
                    class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">Beranda</a>
                <a href="kb.html" class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">KB</a>
                <a href="tk.html" class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">TK</a>
                <a href="galeri.html"
                    class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">Galeri</a>
                <a href="berita.html" class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">Berita</a>
                <a href="index.html#kontak"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-5 rounded-full transition duration-300 transform hover:scale-110">Kontak</a>
            </nav>
            <button id="mobile-menu-button" class="md:hidden text-orange-500 focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </button>
        </div>
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <a href="index.html#beranda" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">Beranda</a>
            <a href="kb.html" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">KB</a>
            <a href="tk.html" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">TK</a>
            <a href="galeri.html" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">Galeri</a>
            <a href="berita.html" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">Berita</a>
            <a href="index.html#kontak" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">Kontak</a>
        </div>
    </header>
    `;

    // Insert the navbar at the beginning of the body
    document.body.insertAdjacentHTML('afterbegin', navbarHTML);

    // Add event listener for mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
}

// Initialize navbar when DOM is loaded
document.addEventListener('DOMContentLoaded', createNavbar);