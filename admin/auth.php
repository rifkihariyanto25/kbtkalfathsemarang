<?php
session_start();
require_once 'config.php';

// Fungsi untuk login
function login($username, $password) {
    global $conn;
    
    // Mencegah SQL injection
    $username = $conn->real_escape_string($username);
    
    // Query untuk mencari user
    $query = "SELECT * FROM admin WHERE username = '$username'";
    $result = $conn->query($query);
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verifikasi password (dalam contoh ini password disimpan dengan hash)
        // Untuk sementara kita gunakan password plaintext untuk demo
        if ($password === 'admin123') { // Dalam produksi gunakan password_verify()
            // Set session
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_nama'] = $user['nama'];
            $_SESSION['is_logged_in'] = true;
            
            return true;
        }
    }
    
    return false;
}

// Fungsi untuk cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
}

// Fungsi untuk logout
function logout() {
    // Hapus semua data session
    session_unset();
    session_destroy();
    
    // Redirect ke halaman login
    header("Location: login.php");
    exit;
}

// Fungsi untuk mengecek apakah user sudah login, jika belum redirect ke halaman login
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}
?>