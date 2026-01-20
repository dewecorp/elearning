<?php
// Turn off error display to prevent corrupting JSON response
error_reporting(0);
ini_set('display_errors', 0);

@session_start();

// Check if we have the required session
if (!isset($_SESSION['admin']) && !isset($_SESSION['pengajar'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Tidak ada sesi aktif. Silakan login kembali.']);
    exit;
}

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

require_once 'sweetalert_helper.php';

// Debug database connection
if (!$db) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal: Tidak dapat terhubung ke database']);
    exit;
}

// Test a simple query to verify the connection works
$test_query = mysqli_query($db, "SELECT 1 as test");
if (!$test_query) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal: Query tidak dapat dijalankan']);
    exit;
}

// Process actions
if(isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if($action === 'tambah') {
        // Handle insert
        $nama_kelas = isset($_POST['nama_kelas']) ? mysqli_real_escape_string($db, $_POST['nama_kelas']) : '';
        $ruang = isset($_POST['ruang']) ? mysqli_real_escape_string($db, $_POST['ruang']) : '';
        $wali_kelas = isset($_POST['wali_kelas']) ? mysqli_real_escape_string($db, $_POST['wali_kelas']) : '';
        $ketua_kelas = isset($_POST['ketua_kelas']) ? mysqli_real_escape_string($db, $_POST['ketua_kelas']) : '';
        
        $insert = mysqli_query($db, "INSERT INTO tb_kelas (nama_kelas, ruang, wali_kelas, ketua_kelas) VALUES ('$nama_kelas', '$ruang', '$wali_kelas', '$ketua_kelas')");
        
        if($insert) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Data kelas berhasil ditambahkan!']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan data kelas: ' . addslashes(mysqli_error($db))]);
        }
    }
    elseif($action === 'edit') {
        // Handle update
        $id = isset($_POST['id']) ? mysqli_real_escape_string($db, $_POST['id']) : '';
        $nama_kelas = isset($_POST['nama_kelas']) ? mysqli_real_escape_string($db, $_POST['nama_kelas']) : '';
        $ruang = isset($_POST['ruang']) ? mysqli_real_escape_string($db, $_POST['ruang']) : '';
        $wali_kelas = isset($_POST['wali_kelas']) ? mysqli_real_escape_string($db, $_POST['wali_kelas']) : '';
        $ketua_kelas = isset($_POST['ketua_kelas']) ? mysqli_real_escape_string($db, $_POST['ketua_kelas']) : '';
        
        // Verify the record exists before updating
        if (!empty($id)) {
            $check_record = mysqli_query($db, "SELECT id_kelas FROM tb_kelas WHERE id_kelas = '$id'");
            if (mysqli_num_rows($check_record) == 0) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Data kelas dengan ID tersebut tidak ditemukan!']);
                exit;
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID kelas tidak boleh kosong!']);
            exit;
        }
        
        $update = mysqli_query($db, "UPDATE tb_kelas SET nama_kelas = '$nama_kelas', ruang = '$ruang', wali_kelas = '$wali_kelas', ketua_kelas = '$ketua_kelas' WHERE id_kelas = '$id'");
        
        if($update) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Data kelas berhasil diperbarui!']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui data kelas: ' . addslashes(mysqli_error($db))]);
        }
    }
}
else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Tidak ada action yang diterima']);
}
?>