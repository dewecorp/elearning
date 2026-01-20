<?php
session_start();

// Attempt to include koneksi.php with multiple fallback paths
$konek_file = __DIR__ . '/../koneksi.php';
if(file_exists($konek_file)) {
    include $konek_file;
} else {
    // Fallback to root koneksi.php
    $konek_file = __DIR__ . '/../../koneksi.php';
    if(file_exists($konek_file)) {
        include $konek_file;
    } else {
        // Final fallback
        include '../koneksi.php';
    }
}

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    $sql = mysqli_query($db, "SELECT * FROM tb_berita WHERE id_berita = '$id'") or die ($db->error);
    
    if(mysqli_num_rows($sql) > 0) {
        $data = mysqli_fetch_assoc($sql);
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Data tidak ditemukan']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'ID tidak ditemukan']);
}
?>