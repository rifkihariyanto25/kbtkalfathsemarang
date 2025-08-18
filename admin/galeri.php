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

// Proses form upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tambah atau edit galeri
    if (isset($_POST['submit_galeri'])) {
        $judul = $_POST['judul'] ?? '';
        $kategori = $_POST['kategori'] ?? '';
        $tanggal = $_POST['tanggal'] ?? '';
        
        // Validasi input
        if (empty($judul) || empty($kategori) || empty($tanggal)) {
            $error = 'Semua field harus diisi';
        } else {
            // Upload gambar jika ada
            $gambar = '';
            $upload_success = false;
            
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
                $target_dir = "../uploads/galeri/";
                
                // Buat direktori jika belum ada
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $file_extension = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
                $new_filename = uniqid() . '.' . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                // Cek tipe file
                $allowed_types = array('jpg', 'jpeg', 'png', 'webp');
                if (in_array($file_extension, $allowed_types)) {
                    // Cek ukuran file (max 5MB)
                    if ($_FILES["gambar"]["size"] <= 5000000) {
                        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                            $gambar = $new_filename;
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
                        $query = "INSERT INTO galeri (judul, kategori, tanggal, gambar) VALUES (?, ?, ?, ?)";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ssss", $judul, $kategori, $tanggal, $gambar);
                        
                        if ($stmt->execute()) {
                            $message = "Foto berhasil ditambahkan";
                            header("Location: galeri.php?message=" . urlencode($message));
                            exit;
                        } else {
                            $error = "Gagal menyimpan data: " . $conn->error;
                        }
                    } else {
                        $error = "Gambar harus diupload";
                    }
                } elseif ($action === 'edit' && $id > 0) {
                    // Edit data yang ada
                    if ($upload_success) {
                        // Jika ada upload gambar baru
                        // Hapus gambar lama
                        $query = "SELECT gambar FROM galeri WHERE id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($row = $result->fetch_assoc()) {
                            $old_image = $row['gambar'];
                            $old_image_path = "../uploads/galeri/" . $old_image;
                            if (file_exists($old_image_path)) {
                                unlink($old_image_path);
                            }
                        }
                        
                        // Update dengan gambar baru
                        $query = "UPDATE galeri SET judul = ?, kategori = ?, tanggal = ?, gambar = ? WHERE id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ssssi", $judul, $kategori, $tanggal, $gambar, $id);
                    } else {
                        // Update tanpa mengubah gambar
                        $query = "UPDATE galeri SET judul = ?, kategori = ?, tanggal = ? WHERE id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("sssi", $judul, $kategori, $tanggal, $id);
                    }
                    
                    if ($stmt->execute()) {
                        $message = "Foto berhasil diperbarui";
                        header("Location: galeri.php?message=" . urlencode($message));
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
    // Ambil info gambar
    $query = "SELECT gambar FROM galeri WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $image = $row['gambar'];
        $image_path = "../uploads/galeri/" . $image;
        
        // Hapus file gambar jika ada
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        
        // Hapus data dari database
        $query = "DELETE FROM galeri WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $message = "Foto berhasil dihapus";
            header("Location: galeri.php?message=" . urlencode($message));
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
    $query = "SELECT * FROM galeri WHERE id = ?";
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

// Ambil data galeri untuk ditampilkan
$galeri_data = [];
if ($action === 'list') {
    $query = "SELECT * FROM galeri ORDER BY tanggal DESC";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $galeri_data[] = $row;
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
    <title>Kelola Galeri - KB-TK Islam Al Fath</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

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
                            <a href="galeri.php" class="sidebar-link active flex items-center px-6 py-3">
                                <i class="fas fa-images mr-3"></i>
                                <span>Galeri</span>
                            </a>
                        </li>
                        <li>
                            <a href="berita.php" class="sidebar-link flex items-center px-6 py-3">
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
                        <h1 class="text-2xl md:text-3xl font-bold">Kelola Galeri</h1>
                        <p class="text-gray-600">Upload dan kelola foto-foto kegiatan KB-TK Al Fath</p>
                    </div>
                    
                    <?php if ($action === 'list'): ?>
                    <a href="?action=add" class="bg-brand-orange hover-brand-orange text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out">
                        <i class="fas fa-plus mr-2"></i> Tambah Foto
                    </a>
                    <?php else: ?>
                    <a href="galeri.php" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out">
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
                        <?php echo $action === 'add' ? 'Upload Foto Baru' : 'Edit Foto'; ?>
                    </h2>

                    <form method="POST" enctype="multipart/form-data" class="space-y-6">
                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                            <?php if ($action === 'edit' && !empty($edit_data['gambar'])): ?>
                            <div class="mb-3">
                                <p class="text-sm text-gray-500 mb-2">Foto saat ini:</p>
                                <img src="../uploads/galeri/<?php echo $edit_data['gambar']; ?>" alt="Current Image" class="h-40 object-cover rounded-lg">
                            </div>
                            <?php endif; ?>
                            
                            <input type="file" name="gambar" id="gambar" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" <?php echo $action === 'add' ? 'required' : ''; ?>>
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WEBP (Max: 5MB)</p>
                        </div>

                        <!-- Form Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Foto</label>
                                <input type="text" id="judul" name="judul" value="<?php echo $action === 'edit' ? htmlspecialchars($edit_data['judul']) : ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                            </div>

                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <select id="kategori" name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="kb" <?php echo ($action === 'edit' && $edit_data['kategori'] === 'kb') ? 'selected' : ''; ?>>KB</option>
                                    <option value="tk" <?php echo ($action === 'edit' && $edit_data['kategori'] === 'tk') ? 'selected' : ''; ?>>TK</option>
                                    <option value="spesial" <?php echo ($action === 'edit' && $edit_data['kategori'] === 'spesial') ? 'selected' : ''; ?>>Acara Spesial</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kegiatan</label>
                                <input type="date" id="tanggal" name="tanggal" value="<?php echo $action === 'edit' ? $edit_data['tanggal'] : ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" name="submit_galeri" class="bg-brand-orange hover-brand-orange text-white font-medium py-2 px-6 rounded-md transition duration-300 ease-in-out">
                                <?php echo $action === 'add' ? 'Upload Foto' : 'Simpan Perubahan'; ?>
                            </button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <!-- Gallery List -->
                <?php if ($action === 'list'): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">Daftar Foto</h2>
                    </div>

                    <?php if (empty($galeri_data)): ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500">Belum ada foto yang diupload</p>
                        <a href="?action=add" class="text-orange-500 hover:text-orange-600 mt-2 inline-block">Upload foto pertama</a>
                    </div>
                    <?php else: ?>
                    <!-- Gallery Items -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($galeri_data as $item): ?>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="relative">
                                <img src="../uploads/galeri/<?php echo $item['gambar']; ?>" alt="<?php echo htmlspecialchars($item['judul']); ?>" class="w-full h-48 object-cover">
                                <div class="absolute top-2 right-2 flex space-x-1">
                                    <a href="?action=edit&id=<?php echo $item['id']; ?>" class="bg-white p-2 rounded-full shadow-md hover:bg-gray-100 transition duration-300 ease-in-out">
                                        <i class="fas fa-edit text-blue-500"></i>
                                    </a>
                                    <a href="?action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?')" class="bg-white p-2 rounded-full shadow-md hover:bg-gray-100 transition duration-300 ease-in-out">
                                        <i class="fas fa-trash text-red-500"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium"><?php echo htmlspecialchars($item['judul']); ?></h3>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-sm text-gray-500"><?php echo date('d F Y', strtotime($item['tanggal'])); ?></span>
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                        <?php 
                                        switch($item['kategori']) {
                                            case 'kb':
                                                echo 'KB';
                                                break;
                                            case 'tk':
                                                echo 'TK';
                                                break;
                                            case 'spesial':
                                                echo 'Acara Spesial';
                                                break;
                                            default:
                                                echo $item['kategori'];
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
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
    </script>
</body>

</html>