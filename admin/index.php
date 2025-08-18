<?php
require_once 'auth.php';

// Cek apakah user sudah login
requireLogin();

// Ambil data admin yang sedang login
$admin_id = $_SESSION['admin_id'];
$admin_username = $_SESSION['admin_username'];
$admin_nama = $_SESSION['admin_nama'];

// Logout process
if (isset($_GET['logout'])) {
    logout();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - KB-TK Islam Al Fath</title>

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
            <a href="?logout=1" class="text-gray-500 focus:outline-none">
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
                            <a href="index.php" class="sidebar-link active flex items-center px-6 py-3">
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
                            <a href="berita.php" class="sidebar-link flex items-center px-6 py-3">
                                <i class="fas fa-newspaper mr-3"></i>
                                <span>Berita</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="absolute bottom-0 w-full p-6">
                    <a href="?logout=1"
                        class="flex items-center text-gray-700 hover:text-red-500 transition duration-300 ease-in-out">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <div class="mb-8">
                    <h1 class="text-2xl md:text-3xl font-bold">Dashboard</h1>
                    <p class="text-gray-600">Selamat datang, <?php echo htmlspecialchars($admin_nama); ?>!</p>
                </div>

                <!-- Dashboard Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Galeri Stats -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-orange-100 text-orange-500 mr-4">
                                <i class="fas fa-images text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">Total Galeri</h3>
                                <?php
                                // Hitung jumlah galeri
                                $query = "SELECT COUNT(*) as total FROM galeri";
                                $result = $conn->query($query);
                                $total_galeri = 0;
                                if ($result && $result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $total_galeri = $row['total'];
                                }
                                ?>
                                <p class="text-3xl font-bold"><?php echo $total_galeri; ?></p>
                            </div>
                        </div>
                        <a href="galeri.php" class="text-orange-500 hover:text-orange-600 text-sm mt-4 inline-block">Kelola Galeri →</a>
                    </div>

                    <!-- Berita Stats -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                                <i class="fas fa-newspaper text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">Total Berita</h3>
                                <?php
                                // Hitung jumlah berita
                                $query = "SELECT COUNT(*) as total FROM berita";
                                $result = $conn->query($query);
                                $total_berita = 0;
                                if ($result && $result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $total_berita = $row['total'];
                                }
                                ?>
                                <p class="text-3xl font-bold"><?php echo $total_berita; ?></p>
                            </div>
                        </div>
                        <a href="berita.php" class="text-blue-500 hover:text-blue-600 text-sm mt-4 inline-block">Kelola Berita →</a>
                    </div>

                    <!-- Admin Stats -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                                <i class="fas fa-user-shield text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">Admin</h3>
                                <p class="text-3xl font-bold">1</p>
                            </div>
                        </div>
                        <span class="text-green-500 text-sm mt-4 inline-block">Aktif</span>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Akses Cepat</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="galeri.php?action=add"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-orange-50 transition duration-300">
                            <div class="p-2 rounded-full bg-orange-100 text-orange-500 mr-3">
                                <i class="fas fa-plus"></i>
                            </div>
                            <span>Tambah Foto Baru</span>
                        </a>
                        <a href="berita.php?action=add"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition duration-300">
                            <div class="p-2 rounded-full bg-blue-100 text-blue-500 mr-3">
                                <i class="fas fa-plus"></i>
                            </div>
                            <span>Tambah Berita Baru</span>
                        </a>
                        <a href="../index.html" target="_blank"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 transition duration-300">
                            <div class="p-2 rounded-full bg-green-100 text-green-500 mr-3">
                                <i class="fas fa-globe"></i>
                            </div>
                            <span>Lihat Website</span>
                        </a>
                    </div>
                </div>
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