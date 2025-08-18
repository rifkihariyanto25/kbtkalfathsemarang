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
$items_per_page = 12;
$offset = ($page - 1) * $items_per_page;

// Query untuk mengambil data galeri
$query = "SELECT * FROM galeri";
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
$galeri_items = [];

while ($row = $result->fetch_assoc()) {
    $galeri_items[] = $row;
}

// Hitung total item untuk pagination
$count_query = "SELECT COUNT(*) as total FROM galeri";

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

// Kelompokkan galeri berdasarkan tanggal untuk tampilan
$galeri_by_date = [];
foreach ($galeri_items as $item) {
    $date = date('j F Y', strtotime($item['tanggal']));
    if (!isset($galeri_by_date[$date])) {
        $galeri_by_date[$date] = [];
    }
    $galeri_by_date[$date][] = $item;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Galeri - KB-TK Islam Al Fath</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- Animate On Scroll (AOS) Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Navbar.js Script -->
    <script src="../js/navbar.js" defer></script>
    
    <!-- Footer.js Script -->
    <script src="../js/footer.js" defer></script>

    <!-- Custom Styles -->
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #ffffff;
        }

        .brand-orange {
            color: #f26622;
        }

        .bg-brand-orange {
            background-color: #f26622;
        }

        .border-brand-orange {
            border-color: #f26622;
        }

        .bg-brand-red {
            background-color: #9a3b1b;
        }

        .text-dark-gray {
            color: #4f4f4f;
        }

        .filter-btn.active {
            background-color: #f26622;
            color: white;
            border-color: #f26622;
        }

        .date-separator {
            display: flex;
            align-items: center;
            text-align: center;
            color: #828282;
            margin: 3rem 0;
        }

        .date-separator::before,
        .date-separator::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }

        .date-separator:not(:empty)::before {
            margin-right: 0.5em;
        }

        .date-separator:not(:empty)::after {
            margin-left: 0.5em;
        }

        /* Hover effect */
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            cursor: pointer;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
            color: white;
            padding: 1rem;
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .gallery-item:hover .gallery-caption {
            opacity: 1;
            transform: translateY(0);
        }

        /* Animasi muncul */
        .gallery-item {
            transition: transform 0.3s ease, opacity 0.3s ease;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 3rem;
        }

        .pagination a {
            padding: 0.5rem 1rem;
            border: 2px solid #e5e5e5;
            border-radius: 0.5rem;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            border-color: #f26622;
            color: #f26622;
        }

        .pagination a.active {
            background-color: #f26622;
            border-color: #f26622;
            color: white;
        }
    </style>
</head>

<body class="text-dark-gray">
    <!-- Navbar akan dimasukkan oleh navbar.js -->

    <main class="container mx-auto px-6 py-16">
        <!-- ===== PAGE HEADER ===== -->
        <section class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">
                Momen Tak Terlupakan di <span class="brand-orange">Al Fath</span>
            </h1>
            <p class="mt-4 text-lg text-dark-gray">
                Lihat keseruan dan petualangan kami di Al-Fath!
            </p>
        </section>

        <!-- ===== FILTER BUTTONS ===== -->
        <section class="flex justify-center items-center space-x-2 md:space-x-4 mb-12" data-aos="fade-up"
            data-aos-delay="100">
            <a href="?page=1" class="filter-btn font-semibold py-2 px-6 rounded-full border-2 border-transparent <?php echo empty($kategori) ? 'active' : 'border-gray-300 text-gray-500'; ?>">
                Semua
            </a>
            <a href="?kategori=kb&page=1" class="filter-btn font-semibold py-2 px-6 rounded-full border-2 border-transparent <?php echo $kategori === 'kb' ? 'active' : 'border-gray-300 text-gray-500'; ?>">
                KB
            </a>
            <a href="?kategori=tk&page=1" class="filter-btn font-semibold py-2 px-6 rounded-full border-2 border-transparent <?php echo $kategori === 'tk' ? 'active' : 'border-gray-300 text-gray-500'; ?>">
                TK
            </a>
            <a href="?kategori=spesial&page=1" class="filter-btn font-semibold py-2 px-6 rounded-full border-2 border-transparent <?php echo $kategori === 'spesial' ? 'active' : 'border-gray-300 text-gray-500'; ?>">
                Acara Spesial
            </a>
        </section>

        <!-- ===== GALLERY ===== -->
        <section id="gallery-grid">
            <?php if (empty($galeri_items)): ?>
            <div class="text-center py-16" data-aos="fade-up">
                <h3 class="text-2xl font-semibold text-gray-600">Belum ada foto untuk ditampilkan</h3>
                <p class="mt-2 text-gray-500">Silakan kembali lagi nanti</p>
            </div>
            <?php else: ?>
                <?php $item_index = 0; ?>
                <?php foreach ($galeri_by_date as $date => $items): ?>
                <div data-aos="fade-up" data-aos-delay="<?php echo $item_index * 100; ?>">
                    <div class="date-separator"><?php echo $date; ?></div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <?php 
                        $items_count = count($items);
                        foreach ($items as $index => $item): 
                            // Tentukan ukuran item berdasarkan posisi dan jumlah item
                            $col_span = 'col-span-1';
                            $row_span = 'row-span-1';
                            
                            // Buat variasi ukuran seperti design statis
                            if ($items_count >= 4) {
                                if ($index == 0) {
                                    $col_span = 'col-span-2';
                                    $row_span = 'row-span-2';
                                } elseif ($index == 1 && $items_count >= 6) {
                                    $col_span = 'col-span-2';
                                    $row_span = 'row-span-2';
                                }
                            } elseif ($items_count >= 2 && $index == 0) {
                                $col_span = 'col-span-2';
                            }
                            
                            // Cek file gambar
                            $image_path = '../uploads/galeri/' . $item['gambar'];
                            $image_path_check = dirname(__DIR__) . '/uploads/galeri/' . $item['gambar'];
                            $default_image = 'https://via.placeholder.com/400x300/f26622/ffffff?text=No+Image';
                            $img_src = file_exists($image_path_check) ? $image_path : $default_image;
                        ?>
                        <div class="gallery-item <?php echo $col_span . ' ' . $row_span; ?>" data-category="<?php echo $item['kategori']; ?>">
                            <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($item['judul']); ?>" />
                            <div class="gallery-caption">
                                <p><?php echo htmlspecialchars($item['judul']); ?></p>
                                <?php if (!empty($item['deskripsi'])): ?>
                                <p class="text-sm opacity-75"><?php echo htmlspecialchars(substr($item['deskripsi'], 0, 50)); ?><?php echo strlen($item['deskripsi']) > 50 ? '...' : ''; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php $item_index++; ?>
                <?php endforeach; ?>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                    <a href="?<?php echo !empty($kategori) ? 'kategori=' . $kategori . '&' : ''; ?>page=<?php echo $page - 1; ?>">
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
                    <a href="?<?php echo !empty($kategori) ? 'kategori=' . $kategori . '&' : ''; ?>page=<?php echo $i; ?>" 
                       class="<?php echo $i === $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                    <a href="?<?php echo !empty($kategori) ? 'kategori=' . $kategori . '&' : ''; ?>page=<?php echo $page + 1; ?>">
                        Selanjutnya &raquo;
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </main>

    <script>
        AOS.init({ duration: 800, once: true });

        // Optional: Add click handler for gallery items to open in lightbox
        document.querySelectorAll('.gallery-item').forEach(item => {
            item.addEventListener('click', function() {
                const img = this.querySelector('img');
                const imgSrc = img.src;
                const imgAlt = img.alt;
                
                // You can integrate with a lightbox library here
                // For now, just open in new tab
                window.open(imgSrc, '_blank');
            });
        });
    </script>
    
    <!-- Footer akan dimasukkan oleh footer.js -->
    <div id="kontak"></div>
</body>

</html>