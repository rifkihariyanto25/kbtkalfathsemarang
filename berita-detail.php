<?php
require_once 'admin/config.php';

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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($berita['judul']); ?> - KB-TK Islam Al Fath</title>

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

        .article-content {
            line-height: 1.8;
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .article-content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .article-content ul,
        .article-content ol {
            margin-left: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .article-content ul {
            list-style-type: disc;
        }

        .article-content ol {
            list-style-type: decimal;
        }

        .article-content li {
            margin-bottom: 0.5rem;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            margin: 1.5rem 0;
            border-radius: 0.5rem;
        }

        .article-content a {
            color: #f26622;
            text-decoration: underline;
        }

        .article-content a:hover {
            color: #e55511;
        }

        .article-content blockquote {
            border-left: 4px solid #f26622;
            padding-left: 1rem;
            font-style: italic;
            margin: 1.5rem 0;
            color: #4b5563;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include 'components/navbar.php'; ?>

    <!-- Article Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <div class="mb-6 flex items-center text-sm text-gray-600" data-aos="fade-up">
                <a href="/kbtkalfathsemarang/index.php" class="hover:text-brand-orange transition duration-300">Beranda</a>
                <span class="mx-2">/</span>
                <a href="/kbtkalfathsemarang/berita.php" class="hover:text-brand-orange transition duration-300">Berita</a>
                <span class="mx-2">/</span>
                <span class="text-gray-800 font-medium">Detail Berita</span>
            </div>

            <!-- Article Header -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8" data-aos="fade-up">
                <?php 
// Cek apakah file thumbnail ada
$image_path = 'uploads/berita/' . $berita['thumbnail'];
$default_image = 'assets/image 7.png'; // Gambar default jika tidak ada
$img_src = file_exists($image_path) ? $image_path : $default_image;
?>
<img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($berita['judul']); ?>" class="w-full h-80 object-cover">
                
                <div class="p-6 md:p-8">
                    <div class="flex items-center mb-4">
                        <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">
                            <?php 
                            switch($berita['kategori']) {
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
                        <span class="text-sm text-gray-500 ml-4">
                            <i class="far fa-calendar-alt mr-1"></i> <?php echo date('d F Y', strtotime($berita['tanggal'])); ?>
                        </span>
                    </div>
                    
                    <h1 class="text-2xl md:text-3xl font-bold mb-6"><?php echo htmlspecialchars($berita['judul']); ?></h1>
                    
                    <div class="article-content">
                        <?php echo $berita['konten']; ?>
                    </div>
                    
                    <!-- Share Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-gray-700 font-medium mb-3">Bagikan:</p>
                        <div class="flex space-x-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700 transition duration-300">
                                <i class="fab fa-facebook-f w-4 h-4 flex items-center justify-center"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($berita['judul']); ?>" target="_blank" class="bg-blue-400 text-white p-2 rounded-full hover:bg-blue-500 transition duration-300">
                                <i class="fab fa-twitter w-4 h-4 flex items-center justify-center"></i>
                            </a>
                            <a href="https://wa.me/?text=<?php echo urlencode($berita['judul'] . ' - https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="bg-green-500 text-white p-2 rounded-full hover:bg-green-600 transition duration-300">
                                <i class="fab fa-whatsapp w-4 h-4 flex items-center justify-center"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Articles -->
            <?php if (!empty($berita_terkait)): ?>
            <div class="mt-12" data-aos="fade-up">
                <h2 class="text-2xl font-bold mb-6">Berita Terkait</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($berita_terkait as $terkait): ?>
                    <a href="/kbtkalfathsemarang/berita-detail.php?id=<?php echo $terkait['id']; ?>" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        <?php 
                        // Cek apakah file thumbnail ada
                        $image_path = 'uploads/berita/' . $terkait['thumbnail'];
                        $default_image = 'assets/image 7.png'; // Gambar default jika tidak ada
                        $img_src = file_exists($image_path) ? $image_path : $default_image;
                        ?>
                        <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($terkait['judul']); ?>" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($terkait['judul']); ?></h3>
                            <p class="text-sm text-gray-500">
                                <i class="far fa-calendar-alt mr-1"></i> <?php echo date('d M Y', strtotime($terkait['tanggal'])); ?>
                            </p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Back Button -->
            <div class="mt-8 text-center" data-aos="fade-up">
                <a href="/kbtkalfathsemarang/berita.php" class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Berita
                </a>
            </div>
        </div>
    </div>

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