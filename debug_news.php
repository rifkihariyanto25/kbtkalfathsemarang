<?php
require_once 'admin/config.php';

// Cek koneksi database
echo "<h2>Database Connection Check</h2>";
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "<p>Database connection successful!</p>";
}

// Query untuk mengambil data berita terbaru
$query = "SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3";
$result = $conn->query($query);

echo "<h2>Query Results</h2>";

if ($result) {
    if ($result->num_rows > 0) {
        echo "<p>Found {$result->num_rows} news articles:</p>";
        echo "<ul>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            echo "ID: {$row['id']} - ";
            echo "Title: {$row['judul']} - ";
            echo "Date: {$row['tanggal']} - ";
            echo "Category: {$row['kategori']} - ";
            echo "Thumbnail: {$row['thumbnail']}";
            echo "</li>";
        }
        
        echo "</ul>";
    } else {
        echo "<p>No news articles found in the database.</p>";
    }
} else {
    echo "<p>Error executing query: " . $conn->error . "</p>";
}

// Cek apakah kolom yang diperlukan ada dalam tabel
echo "<h2>Table Structure Check</h2>";
$tableQuery = "DESCRIBE berita";
$tableResult = $conn->query($tableQuery);

if ($tableResult) {
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    $requiredColumns = ['id', 'judul', 'tanggal', 'kategori', 'thumbnail', 'excerpt', 'konten'];
    $foundColumns = [];
    
    while ($row = $tableResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['Field']}</td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "<td>{$row['Extra']}</td>";
        echo "</tr>";
        
        $foundColumns[] = $row['Field'];
    }
    
    echo "</table>";
    
    // Cek kolom yang hilang
    $missingColumns = array_diff($requiredColumns, $foundColumns);
    if (!empty($missingColumns)) {
        echo "<p style='color: red;'>Warning: Missing required columns: " . implode(', ', $missingColumns) . "</p>";
    } else {
        echo "<p style='color: green;'>All required columns are present.</p>";
    }
} else {
    echo "<p>Error getting table structure: " . $conn->error . "</p>";
}
?>