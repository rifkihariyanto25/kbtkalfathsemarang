// Footer Component for TK-KB Al-Fath

function createFooter() {
    // Create the footer HTML structure
    const footerHTML = `
    <!-- Footer -->
    <footer class="bg-orange-600 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-8 md:mb-0">
                    <h3 class="text-2xl font-bold mb-4">KB-TK Al-Fath</h3>
                    <p class="max-w-xs">Membentuk generasi cerdas, kreatif, dan berakhlak mulia melalui pendidikan
                        inovatif berbasis karakter Islami.</p>
                </div>

                <div class="mb-8 md:mb-0">
                    <h4 class="text-lg font-semibold mb-4">Kontak Kami</h4>
                    <p class="flex items-center mb-2">
                        <i class="fas fa-map-marker-alt mr-3"></i> Jl. Contoh No. 123, Semarang
                    </p>
                    <p class="flex items-center mb-2">
                        <i class="fas fa-phone mr-3"></i> (024) 1234-5678
                    </p>
                    <p class="flex items-center">
                        <i class="fas fa-envelope mr-3"></i> info@tkalkbfath.sch.id
                    </p>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Jam Operasional</h4>
                    <p class="mb-2">Senin - Jumat: 07.00 - 12.00 WIB</p>
                    <p>Sabtu: 07.00 - 11.00 WIB</p>
                </div>
            </div>

            <div class="border-t border-orange-500 mt-8 pt-8 text-center">
                <p>&copy; 2023 KB-TK Al-Fath. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>
    `;

    // Insert the footer at the end of the body
    document.body.insertAdjacentHTML('beforeend', footerHTML);
}

// Initialize footer when DOM is loaded
document.addEventListener('DOMContentLoaded', createFooter);