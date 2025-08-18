<?php
// Konfigurasi database
$host = 'localhost'; // Host database
$username = 'root'; // Username database
$password = ''; // Password database
$database = 'kbtk_alfath'; // Nama database

// Membuat koneksi ke database
try {
    $conn = new mysqli($host, $username, $password, $database);
    
    // Cek koneksi
    if ($conn->connect_error) {
        throw new Exception("Koneksi database gagal: " . $conn->connect_error);
    }
    
    // Set karakter encoding
    $conn->set_charset("utf8");
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>