<?php
// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'kbtk_alfath';

// Membuat koneksi ke database
try {
    $conn = new mysqli($host, $username, $password, $database);
    
    // Cek koneksi
    if ($conn->connect_error) {
        throw new Exception("Koneksi database gagal: " . $conn->connect_error);
    }
    
    echo "<h2>Koneksi database berhasil</h2>";
    
    // Cek tabel berita
    $result = $conn->query("SHOW TABLES LIKE 'berita'");
    if ($result->num_rows > 0) {
        echo "<p>Tabel berita ditemukan</p>";
        
        // Cek struktur tabel berita
        $result = $conn->query("DESCRIBE berita");
        echo "<h3>Struktur tabel berita:</h3>";
        echo "<ul>";
        $has_excerpt = false;
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['Field'] . " - " . $row['Type'] . " - " . ($row['Null'] === 'YES' ? 'NULL' : 'NOT NULL') . "</li>";
            if ($row['Field'] === 'excerpt') {
                $has_excerpt = true;
            }
        }
        echo "</ul>";
        
        if (!$has_excerpt) {
            echo "<p style='color: red;'><strong>Kolom 'excerpt' tidak ditemukan dalam tabel berita!</strong></p>";
            echo "<p>Menambahkan kolom excerpt ke tabel berita...</p>";
            
            // Tambahkan kolom excerpt jika belum ada
            $alter_query = "ALTER TABLE berita ADD COLUMN excerpt TEXT AFTER konten";
            if ($conn->query($alter_query) === TRUE) {
                echo "<p style='color: green;'>Kolom excerpt berhasil ditambahkan!</p>";
            } else {
                echo "<p style='color: red;'>Error menambahkan kolom excerpt: " . $conn->error . "</p>";
            }
        } else {
            echo "<p style='color: green;'>Kolom excerpt sudah ada dalam tabel berita.</p>";
        }
    } else {
        echo "<p>Tabel berita tidak ditemukan</p>";
    }
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>