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

// Fetch all available classes
$sql_kelas = mysqli_query($db, "SELECT * FROM tb_kelas ORDER BY nama_kelas ASC");

$kelas_list = [];
if($sql_kelas) {
    while($kelas = mysqli_fetch_assoc($sql_kelas)) {
        $kelas_list[] = [
            'id_kelas' => $kelas['id_kelas'],
            'nama_kelas' => $kelas['nama_kelas']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($kelas_list);
?>