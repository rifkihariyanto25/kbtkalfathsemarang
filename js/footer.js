// Footer Component for TK-KB ISLAM AL FATH

function createFooter() {
  // Detect if we're in the root directory or in a subdirectory
  const isRoot =
    window.location.pathname.split("/").filter(Boolean).length === 1 ||
    window.location.pathname.endsWith("index.html") ||
    window.location.pathname.endsWith("index.php") ||
    window.location.pathname.endsWith("/");

  // Set the correct path prefix based on location
  const pathPrefix = isRoot ? "" : "../";
  const pagesPrefix = isRoot ? "pages/" : "../pages/";

  // Create the footer HTML structure
  const footerHTML = `
    <!-- Footer -->
    <footer class="bg-red-700 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-8 md:mb-0">
                    <h3 class="text-2xl font-bold mb-4">KB TK ISLAM AL FATH</h3>
                    <p class="max-w-xs">Lahir dari komitmen untuk menghadirkan pendidikan Islam berkualitas sejak usia dini, menjadi fondasi kokoh bagi tumbuh kembang anak-anak di kota Semarang.</p>
                </div>

                <div class="mb-8 md:mb-0">
                    <h4 class="text-lg font-semibold mb-4">Navigasi</h4>
                    <ul>
                        <li class="mb-2"><a href="/" class="hover:text-yellow-200 transition-colors">Beranda</a></li>
                        <li class="mb-2"><a href="${pagesPrefix}kb.html" class="hover:text-yellow-200 transition-colors">KB AL FATH</a></li>
                        <li class="mb-2"><a href="${pagesPrefix}tk.html" class="hover:text-yellow-200 transition-colors">TK ISLAM AL-FATH</a></li>
                        <li class="mb-2"><a href="${pagesPrefix}galeri.php" class="hover:text-yellow-200 transition-colors">Galeri</a></li>
                        <li class="mb-2"><a href="${pagesPrefix}berita.php" class="text-white hover:text-yellow-200 transition-colors">Berita</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Follow Kami</h4>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/profile.php?id=61563999790817&rdid=hNy8sy7wVjCoAnty&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F15poNf9tH2#" target="_blank" class="text-white hover:text-yellow-200 transition-colors">
                            <i class="fab fa-facebook-f text-2xl"></i>
                        </a>
                        <a href="https://www.instagram.com/kbtkislamalfath?utm_source=qr&igsh=MW9hNGxrOGVzcGh5ZA%3D%3D" target="_blank" class="text-white hover:text-yellow-200 transition-colors">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                        <a href="https://www.youtube.com/@kbal-fath7452" target="_blank" class="text-white hover:text-yellow-200 transition-colors">
                            <i class="fab fa-youtube text-2xl"></i>
                        </a>
                        <a href="https://www.tiktok.com/@kbtkislamalfath2" target="_blank" class="text-white hover:text-yellow-200 transition-colors">
                            <i class="fab fa-tiktok text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-red-600 mt-8 pt-8 text-center">
                <p>&copy; 2025 KB TK ISLAM AL FATH. All rights reserved.</p>
            </div>
        </div>
    </footer>
    `;

  // Insert the footer at the end of the body
  document.body.insertAdjacentHTML("beforeend", footerHTML);
}

// Initialize footer when DOM is loaded
document.addEventListener("DOMContentLoaded", createFooter);
