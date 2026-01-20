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
        // Handle insert for siswa registration
        $nis = isset($_POST['nis']) ? mysqli_real_escape_string($db, $_POST['nis']) : '';
        $nama_lengkap = isset($_POST['nama_lengkap']) ? mysqli_real_escape_string($db, $_POST['nama_lengkap']) : '';
        $jenis_kelamin = isset($_POST['jenis_kelamin']) ? mysqli_real_escape_string($db, $_POST['jenis_kelamin']) : '';
        $alamat = isset($_POST['alamat']) ? mysqli_real_escape_string($db, $_POST['alamat']) : '';
        $id_kelas = isset($_POST['id_kelas']) ? mysqli_real_escape_string($db, $_POST['id_kelas']) : '';
        $thn_masuk = isset($_POST['thn_masuk']) ? mysqli_real_escape_string($db, $_POST['thn_masuk']) : '';
        $username = isset($_POST['username']) ? mysqli_real_escape_string($db, $_POST['username']) : '';
        $password = isset($_POST['password']) ? mysqli_real_escape_string($db, $_POST['password']) : '';
        $status = isset($_POST['status']) ? mysqli_real_escape_string($db, $_POST['status']) : '';
        
        $insert = mysqli_query($db, "INSERT INTO tb_siswa (nis, nama_lengkap, jenis_kelamin, alamat, id_kelas, thn_masuk, username, password, status) VALUES ('$nis', '$nama_lengkap', '$jenis_kelamin', '$alamat', '$id_kelas', '$thn_masuk', '$username', md5('$password'), '$status')");
        
        if($insert) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Data siswa berhasil ditambahkan!']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan data siswa: ' . addslashes(mysqli_error($db))]);
        }
    }
    elseif($action === 'edit') {
        // Handle update for siswa
        $id = isset($_POST['id']) ? mysqli_real_escape_string($db, $_POST['id']) : '';
        $nis = isset($_POST['nis']) ? mysqli_real_escape_string($db, $_POST['nis']) : '';
        $nama_lengkap = isset($_POST['nama_lengkap']) ? mysqli_real_escape_string($db, $_POST['nama_lengkap']) : '';
        $jenis_kelamin = isset($_POST['jenis_kelamin']) ? mysqli_real_escape_string($db, $_POST['jenis_kelamin']) : '';
        $alamat = isset($_POST['alamat']) ? mysqli_real_escape_string($db, $_POST['alamat']) : '';
        $id_kelas = isset($_POST['id_kelas']) ? mysqli_real_escape_string($db, $_POST['id_kelas']) : '';
        $thn_masuk = isset($_POST['thn_masuk']) ? mysqli_real_escape_string($db, $_POST['thn_masuk']) : '';
        $username = isset($_POST['username']) ? mysqli_real_escape_string($db, $_POST['username']) : '';
        $password = isset($_POST['password']) ? mysqli_real_escape_string($db, $_POST['password']) : '';
        $status = isset($_POST['status']) ? mysqli_real_escape_string($db, $_POST['status']) : '';
        
        // Verify the record exists before updating
        if (!empty($id)) {
            $check_record = mysqli_query($db, "SELECT id_siswa FROM tb_siswa WHERE id_siswa = '$id'");
            if (mysqli_num_rows($check_record) == 0) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Data siswa dengan ID tersebut tidak ditemukan!']);
                exit;
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID siswa tidak boleh kosong!']);
            exit;
        }
        
        if(!empty($password)) {
            $update = mysqli_query($db, "UPDATE tb_siswa SET nis = '$nis', nama_lengkap = '$nama_lengkap', jenis_kelamin = '$jenis_kelamin', alamat = '$alamat', id_kelas = '$id_kelas', thn_masuk = '$thn_masuk', username = '$username', password = md5('$password'), status = '$status' WHERE id_siswa = '$id'");
        } else {
            $update = mysqli_query($db, "UPDATE tb_siswa SET nis = '$nis', nama_lengkap = '$nama_lengkap', jenis_kelamin = '$jenis_kelamin', alamat = '$alamat', id_kelas = '$id_kelas', thn_masuk = '$thn_masuk', username = '$username', status = '$status' WHERE id_siswa = '$id'");
        }
        
        if($update) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Data siswa berhasil diperbarui!']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui data siswa: ' . addslashes(mysqli_error($db))]);
        }
    }
}
else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Tidak ada action yang diterima']);
}
?>