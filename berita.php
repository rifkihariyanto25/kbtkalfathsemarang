<?php
require_once 'admin/config.php';

// Inisialisasi variabel
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 6;
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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Berita - KB-TK Islam Al Fath</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- AOS Library -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- Custom Styles -->
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f9fafb;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f26622' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
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

        .filter-button {
            transition: all 0.3s ease;
        }

        .filter-button.active {
            background-color: #f26622;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include 'components/navbar.php'; ?>

    <!-- Banner -->
    <section class="relative bg-gray-900 text-white">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('assets/images/banner-berita.jpg');"></div>
        <div class="absolute inset-0 bg-black opacity-60"></div>
        <div class="container mx-auto px-4 py-24 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" data-aos="fade-up">Berita & Pengumuman</h1>
            <p class="text-xl max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">Informasi terbaru seputar kegiatan dan pengumuman KB-TK Islam Al Fath Semarang</p>
        </div>
    </section>

    <!-- Filter Buttons -->
    <section class="py-8 bg-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-center gap-4" data-aos="fade-up">
                <a href="berita.php" class="filter-button px-6 py-2 rounded-full border-2 border-gray-300 font-medium text-gray-700 hover:border-brand-orange hover:text-brand-orange <?php echo empty($kategori) ? 'active' : ''; ?>">
                    Semua
                </a>
                <a href="berita.php?kategori=pengumuman" class="filter-button px-6 py-2 rounded-full border-2 border-gray-300 font-medium text-gray-700 hover:border-brand-orange hover:text-brand-orange <?php echo $kategori === 'pengumuman' ? 'active' : ''; ?>">
                    Pengumuman
                </a>
                <a href="berita.php?kategori=kegiatan" class="filter-button px-6 py-2 rounded-full border-2 border-gray-300 font-medium text-gray-700 hover:border-brand-orange hover:text-brand-orange <?php echo $kategori === 'kegiatan' ? 'active' : ''; ?>">
                    Kegiatan
                </a>
                <a href="berita.php?kategori=prestasi" class="filter-button px-6 py-2 rounded-full border-2 border-gray-300 font-medium text-gray-700 hover:border-brand-orange hover:text-brand-orange <?php echo $kategori === 'prestasi' ? 'active' : ''; ?>">
                    Prestasi
                </a>
            </div>
        </div>
    </section>

    <!-- News List -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <?php if (empty($berita_items)): ?>
            <div class="text-center py-16">
                <h3 class="text-2xl font-semibold text-gray-600">Belum ada berita untuk ditampilkan</h3>
                <p class="mt-2 text-gray-500">Silakan kembali lagi nanti</p>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($berita_items as $index => $item): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="<?php echo $index * 50; ?>">
                    <div class="relative">
                        <?php 
                        // Cek apakah file thumbnail ada
                        $image_path = 'uploads/berita/' . $item['thumbnail'];
                        $default_image = 'assets/image 7.png'; // Gambar default jika tidak ada
                        $img_src = file_exists($image_path) ? $image_path : $default_image;
                        ?>
                        <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($item['judul']); ?>" class="w-full h-48 object-cover">
                        <div class="absolute top-4 right-4">
                            <span class="text-xs bg-white text-gray-800 px-3 py-1 rounded-full shadow-md font-medium">
                                <?php 
                                switch($item['kategori']) {
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
                                        echo ucfirst($item['kategori']);
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($item['judul']); ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($item['excerpt']); ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                <i class="far fa-calendar-alt mr-1"></i> <?php echo date('d M Y', strtotime($item['tanggal'])); ?>
                            </span>
                            <a href="/kbtkalfathsemarang/berita-detail.php?id=<?php echo $item['id']; ?>" class="text-brand-orange hover:text-orange-700 font-medium transition duration-300">
                                Baca Selengkapnya <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="mt-12 flex justify-center">
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
                    <a href="?<?php echo !empty($kategori) ? 'kategori=' . $kategori . '&' : ''; ?>page=<?php echo $i; ?>" class="px-4 py-2 <?php echo $i === $page ? 'bg-brand-orange text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?> rounded-md transition duration-300">
                        <?php echo $i; ?>
                    </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                    <a href="?<?php echo !empty($kategori) ? 'kategori=' . $kategori . '&' : ''; ?>page=<?php echo $page + 1; ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-300">
                        Selanjutnya &raquo;
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>

    <!-- AOS Init -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
        });
    </script>
</body>

</html>