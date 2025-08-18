<?php
require_once 'auth.php';

// Cek jika user sudah login, redirect ke dashboard
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi';
    } else {
        if (login($username, $password)) {
            header("Location: index.php");
            exit;
        } else {
            $error = 'Username atau password salah';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Admin - KB-TK Islam Al Fath</title>

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
    </style>
</head>

<body class="text-gray-800">
    <!-- Login Screen -->
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold brand-orange">Admin Panel</h1>
                <p class="text-gray-600 mt-2">KB-TK Islam Al Fath</p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        required>
                </div>

                <div>
                    <button type="submit"
                        class="w-full bg-brand-orange hover-brand-orange text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out">
                        Login
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <a href="../index.html" class="text-sm text-gray-600 hover:text-orange-500">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>

</html>