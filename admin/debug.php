<?php
// Aktifkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Debug Form Submission</h1>";

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Form disubmit dengan metode POST</h2>";
    
    // Cek apakah submit_berita ada
    if (isset($_POST['submit_berita'])) {
        echo "<p style='color:green'>Button submit_berita ditemukan</p>";
    } else {
        echo "<p style='color:red'>Button submit_berita TIDAK ditemukan</p>";
    }
    
    // Tampilkan semua data POST
    echo "<h3>Data POST:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    // Tampilkan data FILES jika ada upload
    if (!empty($_FILES)) {
        echo "<h3>Data FILES:</h3>";
        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";
    }
} else {
    echo "<p>Tidak ada data POST yang dikirim. Silakan submit form berita.</p>";
    echo "<p>Kembali ke <a href='berita.php?action=add'>halaman tambah berita</a></p>";
}
?>

<h2>Form Test</h2>
<form method="POST" enctype="multipart/form-data">
    <div>
        <label>Judul Test:</label>
        <input type="text" name="judul" value="Test Judul">
    </div>
    <div>
        <label>Kategori Test:</label>
        <select name="kategori">
            <option value="pengumuman">Pengumuman</option>
        </select>
    </div>
    <div>
        <label>Tanggal Test:</label>
        <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>">
    </div>
    <div>
        <label>Konten Test:</label>
        <textarea name="konten">Test konten</textarea>
    </div>
    <div>
        <label>File Test:</label>
        <input type="file" name="thumbnail">
    </div>
    <div>
        <button type="submit" name="submit_berita" value="1">Test Submit</button>
    </div>
</form>