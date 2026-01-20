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

// Fetch all available students
$sql_siswa = mysqli_query($db, "SELECT * FROM tb_siswa ORDER BY nama_lengkap ASC");

$siswa_list = [];
if($sql_siswa) {
    while($siswa = mysqli_fetch_assoc($sql_siswa)) {
        $siswa_list[] = [
            'id_siswa' => $siswa['id_siswa'],
            'nama_lengkap' => $siswa['nama_lengkap']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($siswa_list);
?>