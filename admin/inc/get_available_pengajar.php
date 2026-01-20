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

// Check if user is logged in as admin or pengajar
if (!isset($_SESSION['admin']) && !isset($_SESSION['pengajar'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Fetch all available teachers
$sql_pengajar = mysqli_query($db, "SELECT * FROM tb_pengajar ORDER BY nama_lengkap ASC");

$pengajar_list = [];
if($sql_pengajar) {
    while($pengajar = mysqli_fetch_assoc($sql_pengajar)) {
        $pengajar_list[] = [
            'id_pengajar' => $pengajar['id_pengajar'],
            'nama_lengkap' => $pengajar['nama_lengkap']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($pengajar_list);
?>