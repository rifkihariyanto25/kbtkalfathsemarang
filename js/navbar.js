// Navbar Component for TK-KB Al-Fath

function createNavbar() {
  // Create the navbar HTML structure
  // Detect if we're in the root directory or in a subdirectory
  const isRoot =
    window.location.pathname.split("/").filter(Boolean).length === 1 ||
    window.location.pathname.endsWith("index.html") ||
    window.location.pathname.endsWith("/");

  // Set the correct path prefix based on location
  const pathPrefix = isRoot ? "" : "../";
  const assetsPrefix = isRoot ? "assets/" : "../assets/";
  const pagesPrefix = isRoot ? "pages/" : "../pages/";

  const navbarHTML = `
    <header class="bg-white/80 backdrop-blur-lg sticky top-0 left-0 right-0 z-50 shadow-sm">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a href="${pathPrefix}index.html" class="flex items-center space-x-3">
                <img src="${assetsPrefix}WhatsApp Image 2025-07-30 at 09.47.34_ce7ed10a 1.png" alt="Logo Al-Fath"
                    class="h-12 w-12 rounded-full transform hover:rotate-12 transition-transform duration-300">
                <span class="text-xl font-bold text-gray-800">KB TK Al-Fath</span>
            </a>
            <nav class="hidden md:flex space-x-8 items-center">
                <a href="${pathPrefix}index.html#beranda"
                    class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">Beranda</a>
                <a href="${pagesPrefix}kb.html" class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">KB</a>
                <a href="${pagesPrefix}tk.html" class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">TK</a>
                <a href="${pagesPrefix}galeri.php"
                    class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">Galeri</a>
                <a href="${pagesPrefix}berita.php" class="text-gray-600 hover:text-orange-500 font-semibold transition-colors">Berita</a>
                <a href="${pathPrefix}index.html#kontak"
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
            <a href="${pathPrefix}index.html#beranda" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">Beranda</a>
            <a href="${pagesPrefix}kb.html" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">KB</a>
            <a href="${pagesPrefix}tk.html" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">TK</a>
            <a href="${pagesPrefix}galeri.php" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">Galeri</a>
            <a href="${pagesPrefix}berita.php" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">Berita</a>
            <a href="${pathPrefix}index.html#kontak" class="block py-3 px-6 text-gray-600 hover:bg-orange-50 font-medium">Kontak</a>
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
    if (currentPath.includes("index.html") && linkPath.includes("#beranda")) {
      return true;
    } else if (
      currentPath.includes("kb.html") &&
      linkPath.includes("kb.html")
    ) {
      return true;
    } else if (
      currentPath.includes("tk.html") &&
      linkPath.includes("tk.html")
    ) {
      return true;
    } else if (
      currentPath.includes("galeri.php") &&
      linkPath.includes("galeri.php")
    ) {
      return true;
    } else if (
      currentPath.includes("berita.html") &&
      linkPath.includes("berita.html")
    ) {
      return true;
    } else if (
      currentPath.includes("index.html") &&
      linkPath.includes("#kontak")
    ) {
      return window.location.hash === "#kontak";
    }
    return false;
  };

  // Highlight active link in desktop navbar
  navLinks.forEach((link) => {
    if (isActivePage(link) && !link.classList.contains("bg-red-500")) {
      link.classList.remove("text-gray-600");
      link.classList.add("text-orange-500");
    }
  });

  // Highlight active link in mobile navbar
  mobileNavLinks.forEach((link) => {
    if (isActivePage(link)) {
      link.classList.remove("text-gray-600");
      link.classList.add("text-orange-500");
      link.classList.add("bg-orange-50");
    }
  });
}

// Initialize navbar when DOM is loaded
document.addEventListener("DOMContentLoaded", createNavbar);
