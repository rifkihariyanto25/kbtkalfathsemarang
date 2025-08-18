<?php
require_once 'auth.php';
require_once 'config.php';

// Cek apakah user sudah login
requireLogin();

// Inisialisasi variabel
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';
$error = '';

// Proses form tambah/edit berita
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Log POST data
    error_log("POST data received: " . print_r($_POST, true));
    
    if (isset($_POST['submit_berita'])) {
        $judul = $_POST['judul'] ?? '';
        $kategori = $_POST['kategori'] ?? '';
        $tanggal = $_POST['tanggal'] ?? '';
        $konten = $_POST['konten'] ?? '';
        // Buat excerpt dari konten tanpa tag HTML
        $excerpt = isset($_POST['konten']) ? substr(strip_tags($_POST['konten']), 0, 150) . '...' : '';
        
        // Validasi input
        if (empty($judul) || empty($kategori) || empty($tanggal) || empty($konten)) {
            $error = 'Semua field harus diisi';
        } else {
            // Upload thumbnail jika ada
            $thumbnail = '';
            $upload_success = false;
            
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
                $target_dir = "../uploads/berita/";
                
                // Buat direktori jika belum ada
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $file_extension = strtolower(pathinfo($_FILES["thumbnail"]["name"], PATHINFO_EXTENSION));
                $new_filename = uniqid() . '.' . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                // Cek tipe file
                $allowed_types = array('jpg', 'jpeg', 'png', 'webp');
                if (in_array($file_extension, $allowed_types)) {
                    // Cek ukuran file (max 5MB)
                    if ($_FILES["thumbnail"]["size"] <= 5000000) {
                        if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
                            $thumbnail = $new_filename;
                            $upload_success = true;
                        } else {
                            $error = "Gagal mengupload file.";
                        }
                    } else {
                        $error = "File terlalu besar. Maksimal 5MB.";
                    }
                } else {
                    $error = "Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.";
                }
            }
            
            // Jika tidak ada error, simpan ke database
            if (empty($error)) {
                if ($action === 'add') {
                    // Tambah data baru
                    if ($upload_success) {
                        // Tambahkan field excerpt ke database
                        $query = "INSERT INTO berita (judul, kategori, tanggal, konten, excerpt, thumbnail) VALUES (?, ?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ssssss", $judul, $kategori, $tanggal, $konten, $excerpt, $thumbnail);
                        
                        if ($stmt->execute()) {
                            $message = "Berita berhasil ditambahkan";
                            header("Location: berita.php?message=" . urlencode($message));
                            exit;
                        } else {
                            $error = "Gagal menyimpan data: " . $conn->error;
                        }
                    } else {
                        $error = "Thumbnail harus diupload";
                    }
                } elseif ($action === 'edit' && $id > 0) {
                    // Edit data yang ada
                    if ($upload_success) {
                        // Jika ada upload thumbnail baru
                        // Hapus thumbnail lama
                        $query = "SELECT thumbnail FROM berita WHERE id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($row = $result->fetch_assoc()) {
                            $old_image = $row['thumbnail'];
                            $old_image_path = "../uploads/berita/" . $old_image;
                            if (file_exists($old_image_path)) {
                                unlink($old_image_path);
                            }
                        }
                        
                        // Update dengan thumbnail baru
                        $query = "UPDATE berita SET judul = ?, kategori = ?, tanggal = ?, konten = ?, excerpt = ?, thumbnail = ? WHERE id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ssssssi", $judul, $kategori, $tanggal, $konten, $excerpt, $thumbnail, $id);
                    } else {
                        // Update tanpa mengubah thumbnail
                        $query = "UPDATE berita SET judul = ?, kategori = ?, tanggal = ?, konten = ?, excerpt = ? WHERE id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("sssssi", $judul, $kategori, $tanggal, $konten, $excerpt, $id);
                    }
                    
                    if ($stmt->execute()) {
                        $message = "Berita berhasil diperbarui";
                        header("Location: berita.php?message=" . urlencode($message));
                        exit;
                    } else {
                        $error = "Gagal memperbarui data: " . $conn->error;
                    }
                }
            }
        }
    }
}

// Proses hapus data
if ($action === 'delete' && $id > 0) {
    // Ambil info thumbnail
    $query = "SELECT thumbnail FROM berita WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $image = $row['thumbnail'];
        $image_path = "../uploads/berita/" . $image;
        
        // Hapus file thumbnail jika ada
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        
        // Hapus data dari database
        $query = "DELETE FROM berita WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $message = "Berita berhasil dihapus";
            header("Location: berita.php?message=" . urlencode($message));
            exit;
        } else {
            $error = "Gagal menghapus data: " . $conn->error;
        }
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Ambil data untuk edit
$edit_data = null;
if ($action === 'edit' && $id > 0) {
    $query = "SELECT * FROM berita WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
    } else {
        $error = "Data tidak ditemukan";
        $action = 'list'; // Kembali ke list jika data tidak ditemukan
    }
}

// Ambil data berita untuk ditampilkan
$berita_data = [];
if ($action === 'list') {
    $query = "SELECT * FROM berita ORDER BY tanggal DESC";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $berita_data[] = $row;
        }
    }
}

// Ambil pesan dari URL jika ada
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Berita - KB-TK Islam Al Fath</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- TinyMCE Editor -->
    <script src="https://cdn.tiny.cloud/1/0mytx797xeh11d15p0clvarunr0il02eoly2igl33wcjl9n0/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#konten',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            height: 400,
            setup: function(editor) {
                // Pastikan konten editor disimpan ke textarea sebelum form disubmit
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    </script>

    <!-- Custom Styles -->
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f9fafb;
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

        .hover-brand-orange:hover {
            background-color: #e55511;
        }

        .sidebar {
            transition: all 0.3s ease;
        }

        .sidebar-link {
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background-color: rgba(242, 102, 34, 0.1);
        }

        .sidebar-link.active {
            background-color: #f26622;
            color: white;
        }

        .image-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .drag-area {
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .drag-area.active {
            border-color: #f26622;
            background-color: rgba(242, 102, 34, 0.05);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="text-gray-800">
    <!-- Admin Dashboard -->
    <div class="min-h-screen bg-gray-100">
        <!-- Mobile Header -->
        <header class="md:hidden bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-20">
            <button id="sidebar-toggle" class="text-gray-500 focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-bold brand-orange">Admin Panel</h1>
            <a href="index.php?logout=1" class="text-gray-500 focus:outline-none">
                <i class="fas fa-sign-out-alt text-xl"></i>
            </a>
        </header>

        <div class="flex">
            <!-- Sidebar -->
            <aside id="sidebar"
                class="sidebar fixed md:sticky top-0 left-0 h-screen w-64 bg-white shadow-md z-10 md:transform-none">
                <div class="p-6">
                    <h2 class="text-2xl font-bold brand-orange">KB-TK Al Fath</h2>
                    <p class="text-sm text-gray-600">Admin Panel</p>
                </div>

                <nav class="mt-6">
                    <ul>
                        <li>
                            <a href="index.php" class="sidebar-link flex items-center px-6 py-3">
                                <i class="fas fa-tachometer-alt mr-3"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="galeri.php" class="sidebar-link flex items-center px-6 py-3">
                                <i class="fas fa-images mr-3"></i>
                                <span>Galeri</span>
                            </a>
                        </li>
                        <li>
                            <a href="berita.php" class="sidebar-link active flex items-center px-6 py-3">
                                <i class="fas fa-newspaper mr-3"></i>
                                <span>Berita</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="absolute bottom-0 w-full p-6">
                    <a href="index.php?logout=1"
                        class="flex items-center text-gray-700 hover:text-red-500 transition duration-300 ease-in-out">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <!-- Page Header -->
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold">Kelola Berita</h1>
                        <p class="text-gray-600">Publikasikan dan kelola berita KB-TK Al Fath</p>
                    </div>
                    
                    <?php if ($action === 'list'): ?>
                    <a href="?action=add" class="bg-brand-orange hover-brand-orange text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out">
                        <i class="fas fa-plus mr-2"></i> Tambah Berita
                    </a>
                    <?php else: ?>
                    <a href="berita.php" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Notification Messages -->
                <?php if (!empty($message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $message; ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
                <?php endif; ?>

                <!-- Form Add/Edit -->
                <?php if ($action === 'add' || $action === 'edit'): ?>
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-xl font-semibold mb-4">
                        <?php echo $action === 'add' ? 'Tambah Berita Baru' : 'Edit Berita'; ?>
                    </h2>

                    <form id="beritaForm" method="POST" action="berita.php?action=<?php echo $action; ?><?php echo ($action === 'edit' && $id > 0) ? '&id=' . $id : ''; ?>" enctype="multipart/form-data" class="space-y-6" onsubmit="return validateForm();">
                        <!-- Thumbnail Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail</label>
                            <?php if ($action === 'edit' && !empty($edit_data['thumbnail'])): ?>
                            <div class="mb-3">
                                <p class="text-sm text-gray-500 mb-2">Thumbnail saat ini:</p>
                                <img src="../uploads/berita/<?php echo $edit_data['thumbnail']; ?>" alt="Current Thumbnail" class="h-40 object-cover rounded-lg">
                            </div>
                            <?php endif; ?>
                            
                            <input type="file" name="thumbnail" id="thumbnail" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" <?php echo $action === 'add' ? 'required' : ''; ?>>
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WEBP (Max: 5MB)</p>
                        </div>

                        <!-- Form Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Berita</label>
                                <input type="text" id="judul" name="judul" value="<?php echo $action === 'edit' ? htmlspecialchars($edit_data['judul']) : ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                            </div>

                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <select id="kategori" name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="pengumuman" <?php echo ($action === 'edit' && $edit_data['kategori'] === 'pengumuman') ? 'selected' : ''; ?>>Pengumuman</option>
                                    <option value="kegiatan" <?php echo ($action === 'edit' && $edit_data['kategori'] === 'kegiatan') ? 'selected' : ''; ?>>Kegiatan</option>
                                    <option value="prestasi" <?php echo ($action === 'edit' && $edit_data['kategori'] === 'prestasi') ? 'selected' : ''; ?>>Prestasi</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi</label>
                                <input type="date" id="tanggal" name="tanggal" value="<?php echo $action === 'edit' ? $edit_data['tanggal'] : date('Y-m-d'); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                            </div>

                            <div class="md:col-span-2">
                                <label for="konten" class="block text-sm font-medium text-gray-700 mb-1">Konten Berita</label>
                                <textarea id="konten" name="konten" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required><?php echo $action === 'edit' ? htmlspecialchars($edit_data['konten']) : ''; ?></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" name="submit_berita" value="1" class="bg-brand-orange hover:bg-brand-orange text-white font-medium py-2 px-6 rounded-md transition duration-300 ease-in-out">
                                <?php echo $action === 'add' ? 'Publikasikan Berita' : 'Simpan Perubahan'; ?>
                            </button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <!-- News List -->
                <?php if ($action === 'list'): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">Daftar Berita</h2>
                    </div>

                    <?php if (empty($berita_data)): ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500">Belum ada berita yang dipublikasikan</p>
                        <a href="?action=add" class="text-orange-500 hover:text-orange-600 mt-2 inline-block">Tambah berita pertama</a>
                    </div>
                    <?php else: ?>
                    <!-- News Items -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thumbnail</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($berita_data as $item): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img src="../uploads/berita/<?php echo $item['thumbnail']; ?>" alt="<?php echo htmlspecialchars($item['judul']); ?>" class="h-16 w-24 object-cover rounded">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($item['judul']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
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
                                                    echo $item['kategori'];
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('d F Y', strtotime($item['tanggal'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="?action=edit&id=<?php echo $item['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        <a href="?action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')" class="text-red-600 hover:text-red-900">Hapus</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            
            if (window.innerWidth < 768 && 
                !sidebar.contains(event.target) && 
                !sidebarToggle.contains(event.target) && 
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });
        
        // Validasi form sebelum submit
        function validateForm() {
            // Pastikan TinyMCE menyimpan konten ke textarea
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
            
            // Ambil nilai dari form
            const form = document.getElementById('beritaForm');
            const judul = form.querySelector('[name="judul"]').value.trim();
            const kategori = form.querySelector('[name="kategori"]').value.trim();
            const tanggal = form.querySelector('[name="tanggal"]').value.trim();
            const konten = form.querySelector('[name="konten"]').value.trim();
            const thumbnailInput = form.querySelector('[name="thumbnail"]');
            const action = new URLSearchParams(window.location.search).get('action');
            
            // Validasi field
            if (judul === '') {
                alert('Judul berita harus diisi');
                return false;
            }
            
            if (kategori === '') {
                alert('Kategori berita harus dipilih');
                return false;
            }
            
            if (tanggal === '') {
                alert('Tanggal publikasi harus diisi');
                return false;
            }
            
            if (konten === '') {
                alert('Konten berita harus diisi');
                return false;
            }
            
            // Validasi thumbnail untuk tambah berita baru
            if (action === 'add' && (!thumbnailInput.files || thumbnailInput.files.length === 0)) {
                alert('Thumbnail harus diupload');
                return false;
            }
            
            console.log('Form divalidasi dan disubmit');
            console.log('Judul:', judul);
            console.log('Kategori:', kategori);
            console.log('Tanggal:', tanggal);
            console.log('Konten length:', konten.length);
            
            return true;
        }
        
        // Form submission handler
        document.addEventListener('DOMContentLoaded', function() {
            const beritaForm = document.getElementById('beritaForm');
            if (beritaForm) {
                console.log('Form berita ditemukan');
            } else {
                console.log('Form berita tidak ditemukan');
            }
        });
    </script>
</body>

</html>