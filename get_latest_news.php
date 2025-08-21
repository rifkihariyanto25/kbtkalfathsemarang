<?php
require_once 'admin/config.php';

// Fungsi untuk mengambil 3 berita terbaru
function getLatestNews($limit = 3) {
    global $conn;
    
    // Query untuk mengambil data berita terbaru
    $query = "SELECT * FROM berita ORDER BY tanggal DESC LIMIT ?";
    
    // Prepare dan execute query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $latest_news = [];
    
    while ($row = $result->fetch_assoc()) {
        $latest_news[] = $row;
    }
    
    return $latest_news;
}