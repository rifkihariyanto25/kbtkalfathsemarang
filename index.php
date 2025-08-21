<?php
require_once 'admin/config.php';
require_once 'get_latest_news.php';

// Ambil 3 berita terbaru
$query = "SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3";
$result = $conn->query($query);
$latest_news = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $latest_news[] = $row;
    }
}
?>
<!DOCTYPE html> 
<html lang="id"> 
 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>TK-KB Al-Fath - Cerdas, Kreatif, Berakhlak Mulia</title> 
 
    <!-- Tailwind CSS --> 
    <script src="https://cdn.tailwindcss.com"></script> 
 
    <!-- Google Fonts: Poppins --> 
    <link rel="preconnect" href="https://fonts.googleapis.com"> 
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" 
        rel="stylesheet"> 
         
    <!-- Animate On Scroll (AOS) Library --> 
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" /> 
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script> 
     
    <!-- Font Awesome --> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> 
 
    <!-- Navbar.js Script --> 
    <script src="js/navbar.js" defer></script> 
     
    <style> 
        /* Menggunakan font Poppins yang lebih ramah anak */ 
        body { 
            font-family: 'Poppins', sans-serif; 
            overflow-x: hidden; 
            /* Mencegah scroll horizontal */ 
        } 
 
        .carousel-container, 
        .facilities-carousel-wrapper, 
        .staff-carousel-wrapper { 
            transition: transform 0.5s ease-in-out; 
        } 
 
        /* Pola latar belakang yang lucu */ 
        .pattern-bg { 
            background-color: #fffaf0; 
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='28' height='49' viewBox='0 0 28 49'%3E%3Cg fill-rule='evenodd'%3E%3Cg id='hexagons' fill='%23fef3c7' fill-opacity='0.4' fill-rule='nonzero'%3E%3Cpath d='M13.99 9.25l13 7.5v15l-13 7.5L1 31.75v-15l12.99-7.5zM3 17.9v12.7l10.99 6.34 11-6.35V17.9l-11-6.34L3 17.9zM0 15l12.99 7.5V30L0 22.5zM28 15L15 22.5V30l13-7.5z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); 
        } 
 
        /* Pola doodle untuk hero section */ 
        .hero-doodle-bg { 
            background-color: #ffffff; 
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23fed7aa' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); 
        } 
 
        /* Animasi */ 
        @keyframes float { 
            0% { 
                transform: translateY(0px); 
            } 
 
            50% { 
                transform: translateY(-10px); 
            } 
 
            100% { 
                transform: translateY(0px); 
            } 
        } 
 
        @keyframes pulse-orange { 
            0% { 
                box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.7); 
            } 
 
            70% { 
                box-shadow: 0 0 0 20px rgba(249, 115, 22, 0); 
            } 
 
            100% { 
                box-shadow: 0 0 0 0 rgba(249, 115, 22, 0); 
            } 
        } 
 
        @keyframes draw-path { 
            from { 
                stroke-dashoffset: 1000; 
            } 
 
            to { 
                stroke-dashoffset: 0; 
            } 
        } 
 
        .wavy-underline { 
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg id='squiggle-link' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:ev='http://www.w3.org/2001/xml-events' viewBox='0 0 20 4'%3E%3Cstyle type='text/css'%3E.squiggle{animation:shift .3s linear infinite;}@keyframes shift {from {transform:translateX(0);}to {transform:translateX(-20px);}}%3C/style%3E%3Cpath fill='none' stroke='%23f97316' stroke-width='2' class='squiggle' d='M0,3.5 C 5,3.5 5,0 10,0 C 15,0 15,3.5 20,3.5 C 25,3.5 25,0 30,0 C 35,0 35,3.5 40,3.5'/%3E%3C/svg%3E"); 
            background-position: 0 100%; 
            background-size: auto 6px; 
            background-repeat: repeat-x; 
            text-decoration: none; 
            padding-bottom: 5px; 
        } 
 
        .adventure-path { 
            stroke-dasharray: 1000; 
            animation: draw-path 5s ease-in-out forwards; 
        }
    </style>
</head>

<body class="bg-orange-50/50">

    <!-- Navbar akan dimasukkan oleh navbar.js -->
    <!-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            createNavbar();
        });
    </script> -->

    <main>
        <!-- HERO SECTION BARU -->
        <section id="beranda"
            class="relative w-full min-h-[80vh] md:min-h-[70vh] lg:min-h-[85vh] flex items-center hero-doodle-bg overflow-hidden">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="relative w-full h-[50vh] md:h-[70vh] order-2 md:order-1" data-aos="fade-right" data-aos-duration="1000">
                        <img src="assets/hero-cover2.jpg"
                            alt="[Anak-anak bermain]"
                            class="absolute top-0 left-0 w-3/5 h-3/5 object-cover rounded-3xl shadow-2xl transform hover:scale-105 transition-transform duration-300 -rotate-6">
                        <img src="assets/hero-cover3.jpg"
                            alt="[Anak-anak belajar]"
                            class="absolute bottom-0 right-0 w-1/2 h-1/2 object-cover rounded-3xl shadow-2xl transform hover:scale-105 transition-transform duration-300 rotate-6 border-4 border-white">
                        <img src="assets/hero-cover1.jpg" alt="[Anak menyapa]"
                            class="absolute bottom-1/4 left-1/4 w-2/5 h-2/5 object-cover rounded-full shadow-2xl transform hover:scale-105 transition-transform duration-300 border-4 border-white">
                    </div>
                    <div class="text-center md:text-left order-1 md:order-2" data-aos="fade-left" data-aos-duration="1000">
                        <h1 class="text-4xl lg:text-6xl font-extrabold text-orange-500 leading-tight">KB TK Islam Al
                            Fath</h1>
                        <h2 class="text-2xl lg:text-4xl font-bold text-red-500 mt-2 mb-4"
                            style="text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">Tempat Terbaik Untuk Tumbuh & Bermain</h2>
                        <p class="text-gray-600 text-lg mb-8 max-w-lg mx-auto md:mx-0">Membentuk generasi cerdas,
                            kreatif, dan berakhlak mulia melalui pendidikan inovatif berbasis karakter Islami.</p>
                        <a href="pages/kontak.html"
                            class="hero-button mt-4 px-8 py-4 bg-orange-500 text-white font-bold rounded-full transition duration-300 text-lg hover:bg-orange-600 transform hover:scale-105 inline-block">
                            Mulai Sekarang!</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- VISI MISI SECTION -->
        <section id="visimisi" class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16" data-aos="fade-up">
                    <h2 class="inline-block text-4xl md:text-5xl font-extrabold text-gray-800"><span
                            class="wavy-underline">Visi & Misi Kami</span></h2>
                    <p class="text-gray-600 mt-4 text-lg">Cita-cita besar kami untuk setiap anak hebat di Al-Fath.</p>
                </div>
                <div class="bg-orange-100/50 border-l-8 border-orange-500 rounded-2xl p-8 mb-12 shadow-lg" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-3xl font-bold text-orange-600 mb-3">Visi</h3>
                    <p class="text-xl text-gray-700 italic">"Membentuk kepribadian anak yang islami, berakhlaq mulia,
                        berilmu, beriman, bertaqwa, sehat, cerdas dan ceria"</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-3xl font-bold text-red-600 mb-8 text-center">Misi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-red-100 text-red-500 rounded-full flex items-center justify-center font-bold text-xl">
                                    1</div>
                            </div>
                            <p class="text-gray-600 pt-3">Mengenalkan pada anak tentang rukun Islam dan melatih untuk
                                melaksanakannya</p>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center font-bold text-xl">
                                    2</div>
                            </div>
                            <p class="text-gray-600 pt-3">Menanamkan pembiasaan - pembiasaan yang baik dalam bertingkah
                                laku dan memberikan pengarahan serta nasihat pada anak tentang perbuatan yang baik dan
                                yang buruk</p>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-red-100 text-red-500 rounded-full flex items-center justify-center font-bold text-xl">
                                    3</div>
                            </div>
                            <p class="text-gray-600 pt-3">Memberikan pembelajaran tentang ilmu-ilmu agama dan umum</p>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center font-bold text-xl">
                                    4</div>
                            </div>
                            <p class="text-gray-600 pt-3">Menanamkan keperdayaan kepada anak tentang hal-hal yang harus
                                diimani</p>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-red-100 text-red-500 rounded-full flex items-center justify-center font-bold text-xl">
                                    5</div>
                            </div>
                            <p class="text-gray-600 pt-3">Membiasakan anak untuk selalu mendekatkan diri pada Allah SWT
                                dengan rajin beribadah dan berdo'a</p>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center font-bold text-xl">
                                    6</div>
                            </div>
                            <p class="text-gray-600 pt-3">Melakukan pembiasaan pada warga sekolah untuk selalu menjaga
                                kebersihan diri dan lingkungannya</p>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-red-100 text-red-500 rounded-full flex items-center justify-center font-bold text-xl">
                                    7</div>
                            </div>
                            <p class="text-gray-600 pt-3">Membantu menumbuhkan potensi yang ada pada diri anak sesuai
                                dengan perkembangannya</p>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center font-bold text-xl">
                                    8</div>
                            </div>
                            <p class="text-gray-600 pt-3">Menciptakan kegiatan belajar mengajar yang menyenangkan</p>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-red-100 text-red-500 rounded-full flex items-center justify-center font-bold text-xl">
                                    9</div>
                            </div>
                            <p class="text-gray-600 pt-3">Mengikutsertakan pendidik dan tenaga kependidikan dalam suatu
                                kegiatan untuk meningkatkan mutu pendidikan</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TUJUAN SEKOLAH SECTION -->
        <section id="tujuan" class="py-20 bg-white relative overflow-hidden">
            <div class="absolute top-1/2 -left-24 w-72 h-72 bg-orange-100/50 rounded-full blur-2xl"></div>
            <div class="absolute top-1/3 -right-24 w-72 h-72 bg-red-100/50 rounded-full blur-2xl"></div>

            <div class="container mx-auto px-6 relative z-10">
                <div class="text-center mb-20" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="inline-block text-4xl md:text-5xl font-extrabold text-gray-800"><span
                            class="wavy-underline">Tujuan Sekolah Kami</span></h2>
                    <p class="text-gray-600 mt-4 text-lg">Empat pilar utama yang menjadi target pencapaian kami untuk
                        setiap bintang kecil.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-16 sm:gap-x-8" data-aos="fade-up" data-aos-delay="100">
                    
                <!-- Tujuan 1 -->
                    <div
                        class="relative transform rotate-[-3deg] hover:rotate-0 hover:scale-105 transition-transform duration-300" data-aos="fade-up" data-aos-delay="200">
                        <div
                            class="absolute -top-5 left-1/2 -translate-x-1/2 w-10 h-10 bg-yellow-300 rounded-full shadow-md flex items-center justify-center border-2 border-white">
                            <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                        </div>
                        <div class="bg-orange-100 p-6 rounded-2xl shadow-lg h-full pt-10">
                            <div class="text-center">
                                <img src="assets/tujuan1.png"
                                    class="h-24 w-24 mx-auto mb-4 text-orange-500" alt="Ikon Akhlak Mulia">
                                <h3 class="font-bold text-xl text-orange-700">Pribadi Berakhlak Mulia</h3>
                                <p class="text-sm text-gray-600 mt-2">Menanamkan nilai-nilai Islami agar anak memiliki
                                    akhlak yang baik, sopan, dan menyayangi sesama.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Tujuan 2 -->
                    <div
                        class="relative transform rotate-[3deg] hover:rotate-0 hover:scale-105 transition-transform duration-300 mt-8 sm:mt-0" data-aos="fade-up" data-aos-delay="300">
                        <div
                            class="absolute -top-5 left-1/2 -translate-x-1/2 w-10 h-10 bg-blue-300 rounded-full shadow-md flex items-center justify-center border-2 border-white">
                            <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                        </div>
                        <div class="bg-red-100 p-6 rounded-2xl shadow-lg h-full pt-10">
                            <div class="text-center">
                                <img src="assets/tujuan2.png"
                                    class="h-24 w-24 mx-auto mb-4 text-red-500" alt="Ikon Potensi Anak">
                                <h3 class="font-bold text-xl text-red-700">Potensi Anak Optimal</h3>
                                <p class="text-sm text-gray-600 mt-2">Memberikan stimulasi yang tepat untuk
                                    mengembangkan seluruh aspek kecerdasan anak secara maksimal.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Tujuan 3 -->
                    <div
                        class="relative transform rotate-[-3deg] hover:rotate-0 hover:scale-105 transition-transform duration-300 mt-8 lg:mt-0" data-aos="fade-up" data-aos-delay="400">
                        <div
                            class="absolute -top-5 left-1/2 -translate-x-1/2 w-10 h-10 bg-green-300 rounded-full shadow-md flex items-center justify-center border-2 border-white">
                            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                        </div>
                        <div class="bg-orange-100 p-6 rounded-2xl shadow-lg h-full pt-10">
                            <div class="text-center">
                                <img src="assets/tujuan3.png"
                                    class="h-24 w-24 mx-auto mb-4 text-orange-500" alt="Ikon Minat Belajar">
                                <h3 class="font-bold text-xl text-orange-700">Minat Belajar Tinggi</h3>
                                <p class="text-sm text-gray-600 mt-2">Menciptakan suasana belajar yang seru dan
                                    menyenangkan agar anak tumbuh menjadi pembelajar sejati.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Tujuan 4 -->
                    <div
                        class="relative transform rotate-[3deg] hover:rotate-0 hover:scale-105 transition-transform duration-300 mt-8 lg:mt-0" data-aos="fade-up" data-aos-delay="500">
                        <div
                            class="absolute -top-5 left-1/2 -translate-x-1/2 w-10 h-10 bg-purple-300 rounded-full shadow-md flex items-center justify-center border-2 border-white">
                            <div class="w-4 h-4 bg-purple-500 rounded-full"></div>
                        </div>
                        <div class="bg-red-100 p-6 rounded-2xl shadow-lg h-full pt-10">
                            <div class="text-center">
                                <img src="assets/tujuan4.png"
                                    class="h-24 w-24 mx-auto mb-4 text-red-500" alt="Ikon Generasi Tangguh">
                                <h3 class="font-bold text-xl text-red-700">Generasi Tangguh</h3>
                                <p class="text-sm text-gray-600 mt-2">Membekali anak dengan kemandirian, kepercayaan
                                    diri, dan kemampuan menyelesaikan masalah.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- FASILITAS SECTION -->
        <section id="fasilitas" class="py-20 bg-orange-50/50 pattern-bg">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="inline-block text-4xl md:text-5xl font-extrabold text-gray-800"><span
                            class="wavy-underline">Fasilitas Sekolah Kami</span></h2>
                    <p class="text-gray-600 mt-4 text-lg">Lingkungan belajar yang aman, nyaman, dan menyenangkan untuk
                        si kecil.</p>
                </div>

                <div class="relative" data-aos="fade-up" data-aos-delay="100">
                    <button id="prevFacilityBtn"
                        class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-orange-500 text-white p-3 rounded-full shadow-lg hover:bg-orange-600 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div class="facilities-carousel-wrapper overflow-hidden mx-12">
                        <div class="facilities-carousel flex gap-6" style="transition: transform 0.5s ease-in-out;">
                            <!-- Fasilitas 1: Ruang Kelas -->
                            <div class="facility-card flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-2">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 h-full flex flex-col">
                                    <div class="h-48 overflow-hidden">
                                        <img src="assets/kelas.jpg"
                                            alt="Ruang Kelas" class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-6 flex-grow">
                                        <h3 class="text-xl font-bold text-orange-600 mb-2">Ruang Kelas Nyaman</h3>
                                        <p class="text-gray-600">Ruang kelas yang luas, bersih, dan dilengkapi dengan
                                            peralatan belajar modern untuk mendukung proses pembelajaran.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Fasilitas 2: Taman Bermain -->
                            <div class="facility-card flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-2">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 h-full flex flex-col">
                                    <div class="h-48 overflow-hidden">
                                        <img src="assets/taman-bermain.jpg"
                                            alt="Taman Bermain" class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-6 flex-grow">
                                        <h3 class="text-xl font-bold text-red-600 mb-2">Taman Bermain</h3>
                                        <p class="text-gray-600">Area bermain outdoor yang aman dengan berbagai
                                            permainan untuk mengembangkan motorik kasar anak.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Fasilitas 3: Aula -->
                            <div class="facility-card flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-2">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 h-full flex flex-col">
                                    <div class="h-48 overflow-hidden">
                                        <img src="assets/aula.jpg"
                                            alt="Aula" class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-6 flex-grow">
                                        <h3 class="text-xl font-bold text-orange-600 mb-2">Aula</h3>
                                        <p class="text-gray-600">Ruang aula yang luas dan nyaman menjadi pusat berbagai kegiatan anak. Selain digunakan untuk acara sekolah, 
                                            pertunjukan, dan kegiatan bersama, aula ini juga menjadi tempat latihan ekstrakurikuler drumband.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Fasilitas 4: Kelas Sempoa -->
                            <div class="facility-card flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-2">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 h-full flex flex-col">
                                    <div class="h-48 overflow-hidden">
                                        <img src="assets/kelas-sempoa.jpg" alt="Kelas Sempoa" class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-6 flex-grow">
                                        <h3 class="text-xl font-bold text-orange-600 mb-2">Kelas Sempoa</h3>
                                        <p class="text-gray-600">Pembelajaran sempoa untuk mengembangkan kemampuan
                                            berhitung dan konsentrasi anak sejak dini.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Fasilitas 5: Mushola -->
                            <div class="facility-card flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-2">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 h-full flex flex-col">
                                    <div class="h-48 overflow-hidden">
                                        <img src="assets/mushola.jpg" alt="Mushola"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-6 flex-grow">
                                        <h3 class="text-xl font-bold text-orange-600 mb-2">Mushola</h3>
                                        <p class="text-gray-600">Ruang mushola yang bersih, rapi, dan nyaman membuat kegiatan doa bersama maupun pengenalan ibadah harian menjadi lebih menyenangkan.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Fasilitas 6: Tempat Wudhu -->
                            <div class="facility-card flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-2">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 h-full flex flex-col">
                                    <div class="h-48 overflow-hidden">
                                        <img src="assets/tempat-wudlu.jpg" alt="Tempat Wudhu" class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-6 flex-grow">
                                        <h3 class="text-xl font-bold text-orange-600 mb-2">Tempat Wudhu</h3>
                                        <p class="text-gray-600">Membantu membiasakan siswa menjaga kebersihan diri sekaligus menanamkan kedisiplinan beribadah sejak dini.</p>
                                    </div>
                                </div>
                            </div>


                            <!-- Fasilitas 7: alat bermain dan belajar -->
            <div class="facility-card flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-2">
                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 h-full flex flex-col">
                    <div class="h-48 overflow-hidden">
                        <img src="assets/alat-bermain-dan-belajar.jpg" alt="Alat bermain dan Belajar"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 flex-grow">
                        <h3 class="text-xl font-bold text-orange-600 mb-2">Alat Bermain dan Belajar</h3>
                        <p class="text-gray-600">Fasilitas bermain edukatif yang membuat anak tak hanya belajar membaca, menulis, dan berhitung, tapi juga berani mencoba hal baru dengan cara menyenangkan.</p>
                    </div>
                </div>
            </div>

                            <!-- Fasilitas 8: alat drumband -->
                            <div class="facility-card flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-2">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 h-full flex flex-col">
                                    <div class="h-48 overflow-hidden">
                                        <img src="assets/alat-drumband.jpg" alt="Alat drumband" class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-6 flex-grow">
                                        <h3 class="text-xl font-bold text-orange-600 mb-2">Alat Drumband</h3>
                                        <p class="text-gray-600">Berbagai alat drumband mulai dari bass drum, snare, cymbal, hingga marching bell.
Dengan fasilitas ini, siswa dapat mengasah bakat bermusik, melatih kekompakan, disiplin, dan percaya diri melalui kegiatan ekstrakurikuler drumband.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Fasilitas 9: toilet -->
                            <div class="facility-card flex-shrink-0 w-full md:w-1/2 lg:w-1/3 p-2">
                                <div
                                    class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 h-full flex flex-col">
                                    <div class="h-48 overflow-hidden">
                                        <img src="assets/toilet.jpg" alt="Toilet"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-6 flex-grow">
                                        <h3 class="text-xl font-bold text-orange-600 mb-2">Toilet Bersih dan Terawat</h3>
                                        <p class="text-gray-600">Toilet yang selalu dijaga kebersihannya demi kenyamanan dan kesehatan anak-anak.
Dengan fasilitas ramah anak, toilet ini membantu siswa belajar menjaga kebersihan diri sekaligus menciptakan lingkungan belajar yang sehat.</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <button id="nextFacilityBtn"
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-orange-500 text-white p-3 rounded-full shadow-lg hover:bg-orange-600 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <div id="dots-container" class="flex justify-center mt-6 space-x-2">
                    <span
                        class="dot w-3 h-3 bg-gray-400 rounded-full cursor-pointer transition-colors duration-300"></span>
                    <span
                        class="dot w-3 h-3 bg-gray-400 rounded-full cursor-pointer transition-colors duration-300"></span>
                    <span
                        class="dot w-3 h-3 bg-gray-400 rounded-full cursor-pointer transition-colors duration-300"></span>
                    <span
                        class="dot w-3 h-3 bg-gray-400 rounded-full cursor-pointer transition-colors duration-300"></span>
                    <span
                        class="dot w-3 h-3 bg-gray-400 rounded-full cursor-pointer transition-colors duration-300"></span>
                    <span
                        class="dot w-3 h-3 bg-gray-400 rounded-full cursor-pointer transition-colors duration-300"></span>
                    <span
                        class="dot w-3 h-3 bg-gray-400 rounded-full cursor-pointer transition-colors duration-300"></span>
                    <span
                        class="dot w-3 h-3 bg-gray-400 rounded-full cursor-pointer transition-colors duration-300"></span>
                    <span
                        class="dot w-3 h-3 bg-gray-400 rounded-full cursor-pointer transition-colors duration-300"></span>
                </div>
        </section>
    </main>

<!-- EKSTRAKURIKULER SECTION -->
<section id="ekstrakurikuler" class="py-20 bg-white">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
      <h2 class="inline-block text-4xl md:text-5xl font-extrabold text-gray-800">
        <span class="wavy-underline">Ekstrakulikuler Di KB TK ISLAM AL FATH</span>
      </h2>
      <p class="text-gray-600 mt-4 text-lg">
        Mengasah bakat dan kreativitas si kecil di luar jam belajar!
      </p>
    </div>

    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10" data-aos="fade-up" data-aos-delay="100">
      <!-- Card 1 -->
      <div
        class="bg-gradient-to-br from-orange-100 to-red-100 rounded-3xl shadow-lg hover:shadow-2xl overflow-hidden transition-all duration-300 group transform hover:-translate-y-2"
        data-aos="fade-right" data-aos-delay="200">
        
        <!-- Gambar Cover -->
        <img src="assets/Drumband 1.jpg"
          class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105"
          alt="Marching Band">

        <!-- Konten Card -->
        <div class="p-6 text-center">
          <h3 class="text-2xl font-bold text-gray-800 mb-2">Marching Band</h3>
          <p class="text-gray-600">
            Belajar memainkan alat musik, kekompakan tim, dan disiplin sambil
            berbaris mengikuti irama yang semangat.
          </p>
        </div>
      </div>

      <!-- Card 2 -->
      <div
        class="bg-gradient-to-br from-orange-100 to-red-100 rounded-3xl shadow-lg hover:shadow-2xl overflow-hidden transition-all duration-300 group transform hover:-translate-y-2"
        data-aos="fade-left" data-aos-delay="300">
        
        <!-- Gambar Cover -->
        <img src="assets/sempoa 1.jpg"
          class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105"
          alt="Sempoa">

        <!-- Konten Card -->
        <div class="p-6 text-center">
          <h3 class="text-2xl font-bold text-gray-800 mb-2">Sempoa</h3>
          <p class="text-gray-600">
            Metode berhitung cepat yang seru untuk melatih konsentrasi, daya ingat,
            dan kecerdasan otak kanan dan kiri.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>


        <!-- berita SECTION -->
        <section id="berita" class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="inline-block text-4xl md:text-5xl font-extrabold text-gray-800"><span
                            class="wavy-underline">Berita Terbaru</span></h2>
                    <p class="text-gray-600 mt-4 text-lg">Informasi terkini seputar kegiatan dan prestasi KB-TK Islam
                        Al-Fath</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php if (empty($latest_news)): ?>
                    <div class="col-span-3 text-center py-10">
                        <h3 class="text-xl font-semibold text-gray-600">Belum ada berita untuk ditampilkan</h3>
                        <p class="mt-2 text-gray-500">Silakan kembali lagi nanti</p>
                    </div>
                    <?php else: ?>
                        <?php foreach ($latest_news as $index => $news): ?>
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 group transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="<?php echo ($index + 2) * 100; ?>">
                            <div class="overflow-hidden">
                                <?php 
                                // Cek apakah thumbnail ada dan tidak kosong
                                $image_path = !empty($news['thumbnail']) ? 'uploads/berita/' . $news['thumbnail'] : '';
                                $default_image = 'assets/image 7.png'; // Gambar default jika tidak ada
                                
                                // Gunakan gambar default jika thumbnail kosong atau file tidak ada
                                $img_src = (!empty($image_path) && file_exists($image_path)) ? $image_path : $default_image;
                                ?>
                                <img src="<?php echo $img_src; ?>"
                                    alt="<?php echo htmlspecialchars($news['judul']); ?>"
                                    class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                            </div>
                            <div class="p-6">
                                <span class="text-sm text-orange-600 font-bold">
                                    <?php 
                                    $kategori = isset($news['kategori']) ? $news['kategori'] : '';
                                    switch($kategori) {
                                        case 'pengumuman':
                                            echo 'PENGUMUMAN';
                                            break;
                                        case 'kegiatan':
                                            echo 'KEGIATAN';
                                            break;
                                        case 'prestasi':
                                            echo 'PRESTASI';
                                            break;
                                        default:
                                            echo strtoupper($kategori);
                                    }
                                    ?>
                                </span>
                                <h3 class="text-xl font-bold text-gray-800 mt-2 mb-3"><?php echo htmlspecialchars(isset($news['judul']) ? $news['judul'] : 'Berita Tanpa Judul'); ?></h3>
                                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(isset($news['excerpt']) ? $news['excerpt'] : 'Tidak ada deskripsi'); ?></p>
                                <a href="pages/berita-detail.php?id=<?php echo isset($news['id']) ? $news['id'] : '0'; ?>"
                                    class="font-bold text-orange-500 group-hover:text-red-500 transition">Baca Selengkapnya
                                    &rarr;</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="text-center mt-10">
                    <a href="pages/berita.php" class="inline-block px-6 py-3 bg-orange-500 text-white font-bold rounded-full hover:bg-orange-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">Lihat Semua Berita</a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer akan dimasukkan oleh footer.js -->
    <div id="kontak"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                mirror: false
            });
            
            // Setup facilities carousel
            setupCarousel('facilities-carousel', 'prevFacilityBtn', 'nextFacilityBtn', 'dots-container');
        });
        
        function setupCarousel(carouselClass, prevBtnId, nextBtnId, dotsContainerId) {
            const carousel = document.querySelector('.' + carouselClass);
            const prevBtn = document.getElementById(prevBtnId);
            const nextBtn = document.getElementById(nextBtnId);
            const dots = document.getElementById(dotsContainerId).querySelectorAll('.dot');
            
            let currentIndex = 0;
            let slideWidth;
            let slidesToShow;
            let totalSlides;
            let isAnimating = false;
            let autoSlideInterval;
            
            // Clone slides for infinite effect
            function setupInfiniteSlides() {
                const originalCards = carousel.querySelectorAll('.facility-card');
                totalSlides = originalCards.length;
                
                // Clone first and last slides for infinite effect
                originalCards.forEach((card, index) => {
                    const clone = card.cloneNode(true);
                    clone.setAttribute('aria-hidden', 'true');
                    clone.setAttribute('data-clone', 'true');
                    carousel.appendChild(clone);
                });
            }
            
            // Setup infinite slides
            setupInfiniteSlides();
            
            // Determine number of slides to show based on screen width
            function updateSlidesToShow() {
                if (window.innerWidth < 768) {
                    slidesToShow = 1; // Mobile: 1 slide
                } else if (window.innerWidth < 1024) {
                    slidesToShow = 2; // Tablet: 2 slides
                } else {
                    slidesToShow = 3; // Desktop: 3 slides
                }
                
                // Update slide width
                const cards = carousel.querySelectorAll('.facility-card');
                cards.forEach(card => {
                    card.style.width = `${100 / slidesToShow}%`;
                });
                
                // Calculate slide width including gap
                slideWidth = carousel.querySelector('.facility-card').offsetWidth;
                
                // Move to current slide (to adjust after resize)
                moveToSlide(currentIndex, false);
            }
            
            // Initialize
            updateSlidesToShow();
            window.addEventListener('resize', updateSlidesToShow);
            
            // Update active dot
            function updateDots() {
                // Normalize index for dot display
                const normalizedIndex = currentIndex % totalSlides;
                
                dots.forEach((dot, index) => {
                    if (index === normalizedIndex) {
                        dot.classList.add('bg-orange-500');
                        dot.classList.remove('bg-gray-400');
                    } else {
                        dot.classList.add('bg-gray-400');
                        dot.classList.remove('bg-orange-500');
                    }
                });
            }
            
            // Move to specific slide with smooth transition
            function moveToSlide(index, animate = true) {
                if (isAnimating) return;
                
                currentIndex = index;
                updateDots();
                
                // Apply transition only when animating
                carousel.style.transition = animate ? 'transform 0.5s ease-in-out' : 'none';
                carousel.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
                
                if (animate) {
                    isAnimating = true;
                    setTimeout(() => {
                        isAnimating = false;
                        
                        // Reset position if we've reached a clone
                        if (currentIndex >= totalSlides * 2) {
                            // If we've gone past all clones, jump back to original slides
                            carousel.style.transition = 'none';
                            currentIndex = currentIndex % totalSlides;
                            carousel.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
                        } else if (currentIndex < 0) {
                            // If we've gone before the first slide, jump to end clones
                            carousel.style.transition = 'none';
                            currentIndex = totalSlides - 1;
                            carousel.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
                        }
                    }, 500); // Match the transition duration
                }
            }
            
            // Next slide
            nextBtn.addEventListener('click', () => {
                moveToSlide(currentIndex + 1);
                resetAutoSlide();
            });
            
            // Previous slide
            prevBtn.addEventListener('click', () => {
                moveToSlide(currentIndex - 1);
                resetAutoSlide();
            });
            
            // Dot navigation
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    moveToSlide(index);
                    resetAutoSlide();
                });
            });
            
            // Initialize dots
            updateDots();
            
            // Setup auto slide
            function startAutoSlide() {
                autoSlideInterval = setInterval(() => {
                    moveToSlide(currentIndex + 1);
                }, 5000);
            }
            
            function resetAutoSlide() {
                clearInterval(autoSlideInterval);
                startAutoSlide();
            }
            
            // Start auto sliding
            startAutoSlide();
            
            // Handle transition end for infinite loop effect
            carousel.addEventListener('transitionend', () => {
                // This ensures smooth transition when jumping from clones to original slides
                isAnimating = false;
            });
        }
    </script>
    
    <!-- Footer.js Script --> 
    <script src="js/footer.js"></script>
</body>

</html>