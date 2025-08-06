// Footer Component for TK-KB Al-Fath

function createFooter() {
    // Create the footer HTML structure
    const footerHTML = `
    <!-- Footer -->
    <footer class="bg-red-700 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-8 md:mb-0">
                    <h3 class="text-2xl font-bold mb-4">KB - TK Islam AL Fath</h3>
                    <p class="max-w-xs">Lahir dari komitmen untuk menghadirkan pendidikan Islam berkualitas sejak usia dini, menjadi fondasi kokoh bagi tumbuh kembang anak-anak di kota Semarang.</p>
                </div>

                <div class="mb-8 md:mb-0">
                    <h4 class="text-lg font-semibold mb-4">Navigasi</h4>
                    <ul>
                        <li class="mb-2"><a href="index.html" class="hover:text-yellow-200 transition-colors">Beranda</a></li>
                        <li class="mb-2"><a href="kb.html" class="hover:text-yellow-200 transition-colors">KB Al Fath</a></li>
                        <li class="mb-2"><a href="tk.html" class="hover:text-yellow-200 transition-colors">TK Islam Al-Fath</a></li>
                        <li class="mb-2"><a href="galeri.html" class="hover:text-yellow-200 transition-colors">Galeri</a></li>
                        <li class="mb-2"><a href="berita.html" class="hover:text-yellow-200 transition-colors">Berita</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Follow Kami</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-white hover:text-yellow-200 transition-colors">
                            <i class="fab fa-facebook-f text-2xl"></i>
                        </a>
                        <a href="#" class="text-white hover:text-yellow-200 transition-colors">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                        <a href="#" class="text-white hover:text-yellow-200 transition-colors">
                            <i class="fab fa-youtube text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-red-600 mt-8 pt-8 text-center">
                <p>&copy; 2025 KB-TK Islam Al-Fath. All rights reserved.</p>
            </div>
        </div>
    </footer>
    `;

    // Insert the footer at the end of the body
    document.body.insertAdjacentHTML('beforeend', footerHTML);
}

// Initialize footer when DOM is loaded
document.addEventListener('DOMContentLoaded', createFooter);