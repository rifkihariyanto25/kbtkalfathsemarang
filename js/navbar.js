// Navbar Component for TK-KB ISLAM AL FATH

function createNavbar() {
  // Create the navbar HTML structure
  // Detect if we're in the root directory or in a subdirectory
  const isLocalhost8000 = window.location.port === '8000';
  const baseURL = isLocalhost8000 ? '/' : '/kbtkalfathsemarang/';

  const navbarHTML = `
    <header class="bg-white/80 backdrop-blur-lg sticky top-0 left-0 right-0 z-50 shadow-sm">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a href="${baseURL}" class="flex items-center space-x-3">
                <img src="${baseURL}assets/WhatsApp Image 2025-07-30 at 09.47.34_ce7ed10a 1.png" alt="Logo Al-Fath"
                    class="h-12 w-12 rounded-full transform hover:rotate-12 transition-transform duration-300">
                <span class="text-xl font-bold text-gray-800">KB TK ISLAM AL FATH</span>
            </a>
            <nav class="hidden md:flex space-x-8 items-center">
                <a href="${baseURL}"
                    class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">Beranda</a>
                <a href="${baseURL}pages/kb.html" class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">KB</a>
                <a href="${baseURL}pages/tk.html" class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">TK</a>
                <a href="${baseURL}pages/galeri.php"
                    class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">Galeri</a>
                <a href="${baseURL}pages/berita.php" class="text-gray-600 hover:text-orange-500 active:text-orange-500 font-semibold transition-colors">Berita</a>
                <a href="${baseURL}pages/kontak.html"
                    class="bg-orange-500 text-white px-4 py-2 rounded-[18px] hover:bg-orange-600 font-semibold transition-colors">Kontak</a>
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
            <a href="${baseURL}" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">Beranda</a>
            <a href="${baseURL}pages/kb.html" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">KB</a>
            <a href="${baseURL}pages/tk.html" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">TK</a>
            <a href="${baseURL}pages/galeri.php" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">Galeri</a>
            <a href="${baseURL}pages/berita.php" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 active:text-orange-500 font-medium">Berita</a>
            <a href="${baseURL}pages/kontak.html" class="block py-3 px-6 bg-orange-500 text-white hover:bg-orange-600 font-medium mx-6 my-2 rounded-[18px]">Kontak</a>
        </div>
    </header>
    `;

  // Insert the navbar at the beginning of the body
  document.body.insertAdjacentHTML("afterbegin", navbarHTML);

  // Add event listener for mobile menu toggle
  const mobileMenuButton = document.getElementById("mobile-menu-button");
  const mobileMenu = document.getElementById("mobile-menu");

  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden");
    });
  }

  // Highlight active page in navbar
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll("nav a");
  const mobileNavLinks = document.querySelectorAll("#mobile-menu a");

  // Function to check if a link matches the current page
  const isActivePage = (link) => {
    const linkPath = link.getAttribute("href");

    // Beranda
    if (
      currentPath === "/kbtkalfathsemarang/" ||
      currentPath === "/kbtkalfathsemarang" ||
      currentPath === "/" ||
      currentPath.endsWith("index.php") ||
      currentPath.endsWith("index.html")
    ) {
      return (
        linkPath === baseURL ||
        linkPath === "/" ||
        linkPath === `${baseURL}index.php` ||
        linkPath === `${baseURL}index.html`
      );
    }

    // KB
    if (currentPath.includes("kb.html") && linkPath.includes("kb.html"))
      return true;

    // TK
    if (currentPath.includes("tk.html") && linkPath.includes("tk.html"))
      return true;

    // Galeri
    if (currentPath.includes("galeri.php") && linkPath.includes("galeri.php"))
      return true;

    // Berita
    if (
      (currentPath.includes("berita.php") ||
        currentPath.includes("/pages/berita")) &&
      linkPath.includes("berita.php")
    )
      return true;

    // Kontak
    if (currentPath.includes("kontak.html") && linkPath.includes("kontak.html"))
      return true;

    return false;
  };

  // Highlight active link in desktop navbar
  navLinks.forEach((link) => {
    if (isActivePage(link)) {
      // Hapus class text-gray-600 dan tambahkan text-orange-500
      link.classList.remove("text-gray-600");
      link.classList.add("text-orange-500");

      // Khusus untuk link berita di navbar, tambahkan style agar tetap oranye
      if (link.getAttribute("href").includes("berita.php")) {
        link.style.color = "#f97316"; // Warna orange-500 di Tailwind
      }
    }
  });

  // Highlight active link in mobile navbar
  mobileNavLinks.forEach((link) => {
    if (isActivePage(link)) {
      // Hapus class text-gray-600 dan tambahkan text-orange-500 dan bg-orange-50
      link.classList.remove("text-gray-600");
      link.classList.add("text-orange-500");
      link.classList.add("bg-orange-50");

      // Khusus untuk link berita di mobile navbar, tambahkan style agar tetap oranye
      if (link.getAttribute("href").includes("berita.php")) {
        link.style.color = "#f97316"; // Warna orange-500 di Tailwind
      }
    }
  });
}

// Initialize navbar when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  createNavbar();

  // Tambahan untuk memastikan link berita tetap oranye saat di halaman berita
  setTimeout(() => {
    const currentPath = window.location.pathname;
    if (
      currentPath.includes("berita.php") ||
      currentPath.includes("/pages/berita")
    ) {
      // Hanya pilih link berita yang ada di navbar, bukan di footer
      const beritaLinks = document.querySelectorAll('#navbar a[href*="berita.php"], #mobile-menu a[href*="berita.php"]');
      beritaLinks.forEach((link) => {
        link.style.color = "#f97316"; // Warna orange-500 di Tailwind
        link.classList.remove("text-gray-600");
        link.classList.add("text-orange-500");

        // Jika di mobile menu, tambahkan background
        if (link.closest("#mobile-menu")) {
          link.classList.add("bg-orange-50");
        }
      });
    }
  }, 100); // Delay kecil untuk memastikan navbar sudah dimuat
});
