<?php
require_once 'admin/config.php';

// Query untuk memeriksa data di tabel berita
$query = "SELECT * FROM berita ORDER BY tanggal DESC";
$result = $conn->query($query);

echo "<h2>Checking Database Connection and Data</h2>";

if ($result) {
    echo "<p>Database connection successful!</p>";
    
    if ($result->num_rows > 0) {
        echo "<p>Found {$result->num_rows} news articles in database:</p>";
        echo "<ul>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<li>ID: {$row['id']} - Title: {$row['judul']} - Date: {$row['tanggal']} - Category: {$row['kategori']}</li>";
        }
        
        echo "</ul>";
    } else {
        echo "<p>No news articles found in the database. Please add some news first.</p>";
    }
} else {
    echo "<p>Error querying database: " . $conn->error . "</p>";
}

// Cek struktur tabel
echo "<h3>Table Structure:</h3>";
$tableQuery = "DESCRIBE berita";
$tableResult = $conn->query($tableQuery);

if ($tableResult) {
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while ($row = $tableResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['Field']}</td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "<td>{$row['Extra']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>Error getting table structure: " . $conn->error . "</p>";
}
?>