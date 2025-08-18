<?php
// Buat tabel admin
$sql_admin = "CREATE TABLE IF NOT EXISTS `admin` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `nama` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";  

// Buat tabel galeri
$sql_galeri = "CREATE TABLE IF NOT EXISTS `galeri` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `judul` VARCHAR(255) NOT NULL,
    `gambar` VARCHAR(255) NOT NULL,
    `kategori` ENUM('KB', 'TK', 'Acara Spesial') NOT NULL,
    `tanggal` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";  

// Buat tabel berita
$sql_berita = "CREATE TABLE IF NOT EXISTS `berita` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `judul` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `thumbnail` VARCHAR(255) NOT NULL,
    `konten` TEXT NOT NULL,
    `kategori` ENUM('Pengumuman', 'Kegiatan', 'Prestasi') NOT NULL,
    `tanggal` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";  

// Eksekusi query pembuatan tabel
$conn->query($sql_admin);
$conn->query($sql_galeri);
$conn->query($sql_berita);

// Cek apakah tabel admin kosong, jika ya tambahkan admin default
$result = $conn->query("SELECT COUNT(*) as count FROM admin");
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    // Tambahkan admin default dengan password terenkripsi
    $default_password = password_hash('admin123', PASSWORD_DEFAULT);
    $sql_insert_admin = "INSERT INTO `admin` (`username`, `password`, `nama`, `email`) 
                         VALUES ('admin', '$default_password', 'Administrator', 'admin@kbtkalfath.sch.id')";
    $conn->query($sql_insert_admin);
}
?>