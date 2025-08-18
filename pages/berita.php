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

// Inisialisasi variabel
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 12; // 12 items sesuai dengan layout statis
$offset = ($page - 1) * $items_per_page;

// Query untuk mengambil data berita
$query = "SELECT * FROM berita";
$params = [];
$types = "";

// Filter berdasarkan kategori jika ada
if (!empty($kategori)) {
    $query .= " WHERE kategori = ?";
    $params[] = $kategori;
    $types .= "s";
}

// Tambahkan ordering dan limit
$query .= " ORDER BY tanggal DESC LIMIT ?, ?";
$params[] = $offset;
$params[] = $items_per_page;
$types .= "ii";

// Prepare dan execute query
$stmt = $conn->prepare($query);

// Bind parameters jika ada
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$berita_items = [];

while ($row = $result->fetch_assoc()) {
    $berita_items[] = $row;
}

// Hitung total item untuk pagination
$count_query = "SELECT COUNT(*) as total FROM berita";

if (!empty($kategori)) {
    $count_query .= " WHERE kategori = ?";
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->bind_param("s", $kategori);
} else {
    $count_stmt = $conn->prepare($count_query);
}

$count_stmt->execute();
$count_result = $count_stmt->get_result()->fetch_assoc();
$total_items = $count_result['total'];
$total_pages = ceil($total_items / $items_per_page);

// Pisahkan berita untuk featured article (berita pertama)
$featured_news = !empty($berita_items) ? array_shift($berita_items) : null;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita - KB - TK ISLAM AL FATH</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- AOS CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

    <!-- Navbar.js Script -->
    <script src="../js/navbar.js" defer></script>

    <!-- Footer.js Script -->
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

        .brand-orange {
            color: #f26622;
        }

        .bg-brand-orange {
            background-color: #f26622;
        }

        .filter-button.active {
            background-color: #f26622;
            color: white;
            border-color: #f26622;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header Banner Section -->
    <header class="relative h-96 bg-cover bg-center"
        style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1544717297-fa95b6ee9643?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80');">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white" data-aos="fade-up">
                <h1 class="text-5xl font-bold mb-4" data-aos="fade-up" data-aos-delay="200">Berita</h1>
                <nav aria-label="breadcrumb">
                    <p class="text-lg opacity-90" data-aos="fade-up" data-aos-delay="400">Beberapa Berita Pembelajaran KB - TK ISLAM AL FATH</p>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12 max-w-7xl">

        <?php if (!empty($berita_items) || $featured_news): ?>

        <!-- Berita Baru Section -->
        <section class="mb-16" data-aos="fade-up">
            <header class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800" data-aos="fade-right" data-aos-delay="200">Berita Baru</h2>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php 
                // Ambil 4 berita pertama untuk section "Berita Baru"
                $news_baru = array_slice($berita_items, 0, 4);
                foreach ($news_baru as $index => $item): 
                    // Path gambar
                    $image_path = '../uploads/berita/' . $item['thumbnail'];
                    $image_path_check = dirname(__DIR__) . '/uploads/berita/' . $item['thumbnail'];
                    $default_image = 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60';
                    $img_src = file_exists($image_path_check) ? $image_path : $default_image;
                ?>
                <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300" data-aos="fade-up" data-aos-delay="<?php echo 400 + ($index * 100); ?>">
                    <div class="h-48 overflow-hidden">
                        <img src="<?php echo $img_src; ?>"
                            alt="<?php echo htmlspecialchars($item['judul']); ?>"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-4">
                        <header>
                            <h3 class="font-bold text-gray-800 mb-2 line-clamp-2">
                                <?php echo htmlspecialchars($item['judul']); ?>
                            </h3>
                        </header>
                        <p class="text-sm text-gray-600 mb-3">
                            <?php echo htmlspecialchars(substr($item['excerpt'], 0, 100)) . '...'; ?>
                        </p>
                        <div class="text-xs text-orange-500 flex items-center">
                            <i class="far fa-calendar-alt mr-1"></i>
                            <time datetime="<?php echo $item['tanggal']; ?>"><?php echo date('d F Y', strtotime($item['tanggal'])); ?></time>
                        </div>
                        <div class="mt-2">
                            <a href="berita-detail.php?id=<?php echo $item['id']; ?>" class="text-xs text-white bg-red-500 hover:bg-red-600 font-medium px-3 py-1 rounded-md transition duration-300 ease-in-out flex items-center inline-flex">
                                Baca Selengkapnya
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Featured Article Section -->
        <?php if ($featured_news): ?>
        <section class="mb-16" data-aos="fade-up">
            <article class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="p-8 lg:p-12 flex flex-col justify-center" data-aos="fade-right" data-aos-delay="200">
                        <header class="mb-6">
                            <h2 class="text-4xl font-bold text-gray-800 mb-4 leading-tight">
                                <?php echo htmlspecialchars($featured_news['judul']); ?>
                            </h2>
                        </header>
                        <p class="text-gray-600 leading-relaxed mb-6 text-lg">
                            <?php echo htmlspecialchars($featured_news['excerpt']); ?>
                        </p>
                        <div class="mb-6">
                            <a href="berita-detail.php?id=<?php echo $featured_news['id']; ?>" class="inline-block bg-orange-500 text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-orange-600 transition duration-300">
                                Baca selengkapnya
                            </a>
                        </div>
                    </div>
                    <div class="h-64 lg:h-auto" data-aos="fade-left" data-aos-delay="400">
                        <?php 
                        $featured_image_path = '../uploads/berita/' . $featured_news['thumbnail'];
                        $featured_image_path_check = dirname(__DIR__) . '/uploads/berita/' . $featured_news['thumbnail'];
                        $featured_default_image = 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
                        $featured_img_src = file_exists($featured_image_path_check) ? $featured_image_path : $featured_default_image;
                        ?>
                        <img src="<?php echo $featured_img_src; ?>"
                            alt="<?php echo htmlspecialchars($featured_news['judul']); ?>" class="w-full h-full object-cover">
                    </div>
                </div>
            </article>
        </section>
        <?php endif; ?>

        <!-- More News Grid Section -->
        <?php if (count($berita_items) > 4): ?>
        <section class="mb-16" data-aos="fade-up">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php 
                // Ambil sisa berita untuk grid section
                $more_news = array_slice($berita_items, 4, 8);
                foreach ($more_news as $index => $item): 
                    $image_path = '../uploads/berita/' . $item['thumbnail'];
                    $image_path_check = dirname(__DIR__) . '/uploads/berita/' . $item['thumbnail'];
                    $default_image = 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60';
                    $img_src = file_exists($image_path_check) ? $image_path : $default_image;
                ?>
                <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300" data-aos="fade-up" data-aos-delay="<?php echo 200 + ($index * 100); ?>">
                    <div class="h-48 overflow-hidden">
                        <img src="<?php echo $img_src; ?>"
                            alt="<?php echo htmlspecialchars($item['judul']); ?>"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-4">
                        <header>
                            <h3 class="font-bold text-gray-800 mb-2 line-clamp-2">
                                <?php echo htmlspecialchars($item['judul']); ?>
                            </h3>
                        </header>
                        <p class="text-sm text-gray-600 mb-3">
                            <?php echo htmlspecialchars(substr($item['excerpt'], 0, 100)) . '...'; ?>
                        </p>
                        <div class="text-xs text-orange-500 flex items-center">
                            <i class="far fa-calendar-alt mr-1"></i>
                            <time datetime="<?php echo $item['tanggal']; ?>"><?php echo date('d F Y', strtotime($item['tanggal'])); ?></time>
                        </div>
                        <div class="mt-2">
                            <a href="berita-detail.php?id=<?php echo $item['id']; ?>" class="text-xs text-white bg-red-500 hover:bg-red-600 font-medium px-3 py-1 rounded-md transition duration-300 ease-in-out flex items-center inline-flex">
                                Baca Selengkapnya
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Filter Buttons (jika diperlukan) -->
        <section class="mb-8" data-aos="fade-up">
            <div class="flex flex-wrap justify-center gap-4">
                <a href="?page=1" class="filter-button px-6 py-2 rounded-full border-2 border-gray-300 font-medium text-gray-700 hover:border-orange-500 hover:text-orange-500 <?php echo empty($kategori) ? 'active' : ''; ?>">
                    Semua
                </a>
                <a href="?kategori=pengumuman&page=1" class="filter-button px-6 py-2 rounded-full border-2 border-gray-300 font-medium text-gray-700 hover:border-orange-500 hover:text-orange-500 <?php echo $kategori === 'pengumuman' ? 'active' : ''; ?>">
                    Pengumuman
                </a>
                <a href="?kategori=kegiatan&page=1" class="filter-button px-6 py-2 rounded-full border-2 border-gray-300 font-medium text-gray-700 hover:border-orange-500 hover:text-orange-500 <?php echo $kategori === 'kegiatan' ? 'active' : ''; ?>">
                    Kegiatan
                </a>
                <a href="?kategori=prestasi&page=1" class="filter-button px-6 py-2 rounded-full border-2 border-gray-300 font-medium text-gray-700 hover:border-orange-500 hover:text-orange-500 <?php echo $kategori === 'prestasi' ? 'active' : ''; ?>">
                    Prestasi
                </a>
            </div>
        </section>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <section class="mt-12 flex justify-center" data-aos="fade-up">
            <div class="flex space-x-2">
                <?php if ($page > 1): ?>
                <a href="?<?php echo !empty($kategori) ? 'kategori=' . $kategori . '&' : ''; ?>page=<?php echo $page - 1; ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-300">
                    &laquo; Sebelumnya
                </a>
                <?php endif; ?>
                
                <?php 
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $start_page + 4);
                
                if ($end_page - $start_page < 4 && $start_page > 1) {
                    $start_page = max(1, $end_page - 4);
                }
                
                for ($i = $start_page; $i <= $end_page; $i++): 
                ?>
                <a href="?<?php echo !empty($kategori) ? 'kategori=' . $kategori . '&' : ''; ?>page=<?php echo $i; ?>" class="px-4 py-2 <?php echo $i === $page ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?> rounded-md transition duration-300">
                    <?php echo $i; ?>
                </a>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                <a href="?<?php echo !empty($kategori) ? 'kategori=' . $kategori . '&' : ''; ?>page=<?php echo $page + 1; ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-300">
                    Selanjutnya &raquo;
                </a>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php else: ?>
        <!-- No News Available -->
        <section class="text-center py-16" data-aos="fade-up">
            <h3 class="text-2xl font-semibold text-gray-600">Belum ada berita untuk ditampilkan</h3>
            <p class="mt-2 text-gray-500">Silakan kembali lagi nanti</p>
        </section>
        <?php endif; ?>

    </main>

    <script>
        // Add click interaction to articles
        document.querySelectorAll('article').forEach(article => {
            article.addEventListener('click', function () {
                // Add click animation
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 100);
            });
        });

        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>

    <!-- Footer akan dimasukkan oleh footer.js -->
    <div id="kontak"></div>
</body>

</html>