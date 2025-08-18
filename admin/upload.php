<?php
require_once 'auth.php';
require_once 'config.php';

// Cek apakah user sudah login
requireLogin();

// Fungsi untuk menghasilkan nama file unik
function generateUniqueFilename($filename) {
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    return uniqid() . '.' . $extension;
}

// Direktori untuk menyimpan gambar
$target_dir = "../uploads/berita/";

// Pastikan direktori ada
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Cek apakah ada file yang diupload
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $file_extension = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
    $new_filename = generateUniqueFilename($_FILES["file"]["name"]);
    $target_file = $target_dir . $new_filename;
    
    // Cek tipe file
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    if (in_array($file_extension, $allowed_types)) {
        // Cek ukuran file (max 5MB)
        if ($_FILES["file"]["size"] <= 5000000) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                // Berhasil upload, kirim URL gambar ke TinyMCE
                $file_url = '../uploads/berita/' . $new_filename;
                echo json_encode(['location' => $file_url]);
                exit;
            } else {
                // Gagal upload
                header('HTTP/1.1 500 Internal Server Error');
                echo json_encode(['error' => 'Gagal mengupload file.']);
                exit;
            }
        } else {
            // File terlalu besar
            header('HTTP/1.1 413 Request Entity Too Large');
            echo json_encode(['error' => 'File terlalu besar. Maksimal 5MB.']);
            exit;
        }
    } else {
        // Format file tidak didukung
        header('HTTP/1.1 415 Unsupported Media Type');
        echo json_encode(['error' => 'Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.']);
        exit;
    }
} else {
    // Tidak ada file yang diupload
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Tidak ada file yang diupload.']);
    exit;
}