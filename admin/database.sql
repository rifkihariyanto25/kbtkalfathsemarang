-- Membuat database jika belum ada
CREATE DATABASE IF NOT EXISTS kbtk_alfath;

-- Menggunakan database
USE kbtk_alfath;

-- Membuat tabel galeri
CREATE TABLE IF NOT EXISTS galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    kategori ENUM('kb', 'tk', 'spesial') NOT NULL,
    tanggal DATE NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Membuat tabel berita
CREATE TABLE IF NOT EXISTS berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    konten TEXT NOT NULL,
    thumbnail VARCHAR(255) NOT NULL,
    kategori ENUM('pengumuman', 'kegiatan', 'prestasi') NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Membuat tabel admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Menambahkan admin default (username: admin, password: admin123)
INSERT INTO admin (username, password, nama) VALUES ('admin', '$2y$10$8tPjdlv.K4A/zKN3Tpl9/.4XNPqGXCZ3lAcqBGxj5MJK.9.7e8WLG', 'Administrator');