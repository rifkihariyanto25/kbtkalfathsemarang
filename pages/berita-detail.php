<?php
// Coba beberapa kemungkinan lokasi file config.php
$config_paths = [
    '../admin/config.php',
    '../config.php',
    '../../admin/config.php',
    dirname(__DIR__) . '/admin/config.php',
    dirname(__DIR__) . '/config.php'
];

$config_loaded = false;
foreach ($config_paths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $config_loaded = true;
        break;
    }
}

if (!$config_loaded) {
    die("Error: File config.php tidak ditemukan. Pastikan path ke config.php sudah benar.");
}

// Ambil ID berita dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Jika ID tidak valid, redirect ke halaman berita
if ($id <= 0) {
    header('Location: berita.php');
    exit;
}

// Query untuk mengambil detail berita
$query = "SELECT * FROM berita WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Jika berita tidak ditemukan, redirect ke halaman berita
if ($result->num_rows === 0) {
    header('Location: berita.php');
    exit;
}

// Ambil data berita
$berita = $result->fetch_assoc();

// Query untuk mengambil berita terkait (berita lain dengan kategori yang sama)
$query_terkait = "SELECT id, judul, thumbnail, tanggal FROM berita WHERE kategori = ? AND id != ? ORDER BY tanggal DESC LIMIT 3";
$stmt_terkait = $conn->prepare($query_terkait);
$stmt_terkait->bind_param("si", $berita['kategori'], $id);
$stmt_terkait->execute();
$result_terkait = $stmt_terkait->get_result();
$berita_terkait = [];

while ($row = $result_terkait->fetch_assoc()) {
    $berita_terkait[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($berita['judul']); ?> - KB - TK ISLAM AL FATH</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Navbar.js and Footer.js Scripts -->
    <script src="../js/navbar.js" defer></script>
    <script src="../js/footer.js" defer></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        /* Pola latar belakang yang lucu */
        .pattern-bg {
            background-color: #fffaf0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='28' height='49' viewBox='0 0 28 49'%3E%3Cg fill-rule='evenodd'%3E%3Cg id='hexagons' fill='%23fef3c7' fill-opacity='0.4' fill-rule='nonzero'%3E%3Cpath d='M13.99 9.25l13 7.5v15l-13 7.5L1 31.75v-15l12.99-7.5zM3 17.9v12.7l10.99 6.34 11-6.35V17.9l-11-6.34L3 17.9zM0 15l12.99 7.5V30L0 22.5zM28 15L15 22.5V30l13-7.5z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            position: relative;
            overflow: hidden;
        }

        .content-area img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
        }

        .content-area p {
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navbar will be inserted by navbar.js -->
    
    <!-- Header Banner Section -->
    <header class="relative h-64 bg-cover bg-center"
        style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1544717297-fa95b6ee9643?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80');">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white">
                <h1 class="text-4xl font-bold mb-2"><?php echo htmlspecialchars($berita['judul']); ?></h1>
                <div class="flex items-center justify-center space-x-4 text-sm">
                    <span class="flex items-center">
                        <i class="far fa-calendar-alt mr-1"></i>
                        <time><?php echo date('d F Y', strtotime($berita['tanggal'])); ?></time>
                    </span>
                    <span class="flex items-center">
                        <i class="far fa-folder mr-1"></i>
                        <span>
                            <?php
                            switch ($berita['kategori']) {
                                case 'pengumuman':
                                    echo 'Pengumuman';
                                    break;
                                case 'kegiatan':
                                    echo 'Kegiatan';
                                    break;
                                case 'prestasi':
                                    echo 'Prestasi';
                                    break;
                                default:
                                    echo ucfirst($berita['kategori']);
                            }
                            ?>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Breadcrumb -->
    <div class="bg-gray-100 py-3">
        <div class="container mx-auto px-4 max-w-7xl">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="../index.html" class="text-gray-700 hover:text-orange-500 text-sm">
                            <i class="fas fa-home mr-1"></i> Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="berita.php" class="text-gray-700 hover:text-orange-500 text-sm">Berita</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="text-gray-500 text-sm truncate max-w-xs"><?php echo htmlspecialchars($berita['judul']); ?></span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12 max-w-5xl">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Featured Image -->
            <div class="w-full h-96 overflow-hidden">
                <?php
                // Cek apakah file thumbnail ada
                $image_path = '../uploads/berita/' . $berita['thumbnail'];
                $image_path_check = dirname(__DIR__) . '/uploads/berita/' . $berita['thumbnail'];
                $default_image = 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80';
                $img_src = file_exists($image_path_check) ? $image_path : $default_image;
                ?>
                <img src="<?php echo $img_src; ?>"
                    alt="<?php echo htmlspecialchars($berita['judul']); ?>" class="w-full h-full object-cover">
            </div>

            <!-- Content -->
            <div class="p-6 md:p-10">
                <!-- Category Badge -->
                <div class="mb-6">
                    <span class="px-3 py-1 text-sm rounded-full bg-orange-100 text-orange-800">
                        <?php
                        switch ($berita['kategori']) {
                            case 'pengumuman':
                                echo 'Pengumuman';
                                break;
                            case 'kegiatan':
                                echo 'Kegiatan';
                                break;
                            case 'prestasi':
                                echo 'Prestasi';
                                break;
                            default:
                                echo ucfirst($berita['kategori']);
                        }
                        ?>
                    </span>
                </div>

                <!-- Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6"><?php echo htmlspecialchars($berita['judul']); ?></h1>

                <!-- Meta Info -->
                <div class="flex items-center text-gray-500 text-sm mb-8">
                    <span class="flex items-center">
                        <i class="far fa-calendar-alt mr-1"></i>
                        <time><?php echo date('d F Y', strtotime($berita['tanggal'])); ?></time>
                    </span>
                </div>

                <!-- Content Area -->
                <div class="content-area text-gray-700 leading-relaxed">
                    <?php echo $berita['konten']; ?>
                </div>

                <!-- Share Buttons -->
                <div class="mt-10 pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-semibold mb-4">Bagikan Berita Ini</h4>
                    <div class="flex space-x-3">
                        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out">
                            <i class="fab fa-facebook-f mr-2"></i> Facebook
                        </a>
                        <a href="#" class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out">
                            <i class="fab fa-twitter mr-2"></i> Twitter
                        </a>
                        <a href="#" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out">
                            <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related News -->
        <section class="mt-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Berita Terkait</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php if (!empty($berita_terkait)): ?>
                    <?php foreach ($berita_terkait as $terkait): ?>
                        <?php
                        // Cek apakah file thumbnail ada
                        $image_path = '../uploads/berita/' . $terkait['thumbnail'];
                        $image_path_check = dirname(__DIR__) . '/uploads/berita/' . $terkait['thumbnail'];
                        $default_image = 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60';
                        $img_src = file_exists($image_path_check) ? $image_path : $default_image;
                        ?>
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <a href="berita-detail.php?id=<?php echo $terkait['id']; ?>" class="block">
                                <div class="h-48 overflow-hidden">
                                    <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($terkait['judul']); ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-gray-800 mb-2 line-clamp-2"><?php echo htmlspecialchars($terkait['judul']); ?></h3>
                                    <div class="text-xs text-orange-500 flex items-center">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        <time datetime="<?php echo $terkait['tanggal']; ?>"><?php echo date('d F Y', strtotime($terkait['tanggal'])); ?></time>
                                    </div>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-3 text-center py-8 text-gray-500">
                        <p>Tidak ada berita terkait saat ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="fixed bottom-6 right-6 bg-orange-500 text-white p-3 rounded-full shadow-lg opacity-0 transition-opacity duration-300">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Container for footer -->
    <div id="kontak"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize navbar
            // if (typeof createNavbar === 'function') {
            //     createNavbar();
            // }
            
            // Back to top button functionality
            const backToTopButton = document.getElementById('back-to-top');
            
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('opacity-100');
                } else {
                    backToTopButton.classList.remove('opacity-100');
                }
            });
            
            backToTopButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>