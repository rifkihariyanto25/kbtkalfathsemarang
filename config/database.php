<?php
// Konfigurasi database
$host = 'localhost'; // Host database
$username = 'root';  // Username database (default: root)
$password = '';      // Password database (default kosong untuk XAMPP)
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
    // Cek apakah database belum ada
    if (strpos($e->getMessage(), "Unknown database") !== false) {
        // Buat koneksi tanpa database untuk membuat database
        $conn = new mysqli($host, $username, $password);
        
        // Buat database
        $sql = "CREATE DATABASE IF NOT EXISTS $database CHARACTER SET utf8 COLLATE utf8_general_ci";
        if ($conn->query($sql) === TRUE) {
            // Pilih database yang baru dibuat
            $conn->select_db($database);
            
            // Buat tabel-tabel yang diperlukan
            include_once 'database_schema.php';
            
            echo "<div class='alert alert-success'>Database berhasil dibuat.</div>";
        } else {
            die("<div class='alert alert-danger'>Error membuat database: " . $conn->error . "</div>");
        }
    } else {
        die("<div class='alert alert-danger'>Koneksi database gagal: " . $e->getMessage() . "</div>");
    }
}

// Fungsi untuk membersihkan input
function clean($conn, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Fungsi untuk menghasilkan slug dari judul
function createSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', ' ', $string);
    $string = preg_replace('/\s/', '-', $string);
    return $string;
}

// Fungsi untuk format tanggal Indonesia
function formatTanggal($date) {
    $bulan = array (
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $split = explode('-', $date);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}
?>