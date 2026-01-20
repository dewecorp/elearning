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
        if(@$_SESSION['admin']) {
            // Handle insert for mapel (admin)
            $kode_mapel = isset($_POST['kode_mapel']) ? mysqli_real_escape_string($db, $_POST['kode_mapel']) : '';
            $mapel = isset($_POST['mapel']) ? mysqli_real_escape_string($db, $_POST['mapel']) : '';
            
            $insert = mysqli_query($db, "INSERT INTO tb_mapel VALUES(null, '$kode_mapel', '$mapel')");
            
            if($insert) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Mata pelajaran berhasil ditambahkan!']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan mata pelajaran: ' . addslashes(mysqli_error($db))]);
            }
        } else if(@$_SESSION['pengajar']) {
            // Handle insert for mapel_ajar (pengajar)
            $mapel = isset($_POST['mapel']) ? mysqli_real_escape_string($db, $_POST['mapel']) : '';
            $kelas = isset($_POST['kelas']) ? mysqli_real_escape_string($db, $_POST['kelas']) : '';
            $pengajar = $_SESSION['pengajar'];
            $ket = isset($_POST['ket']) ? mysqli_real_escape_string($db, $_POST['ket']) : '';
            
            $insert = mysqli_query($db, "INSERT INTO tb_mapel_ajar VALUES(null, '$mapel', '$kelas', '$pengajar', '$ket')");
            
            if($insert) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Mata pelajaran yang diajar berhasil ditambahkan!']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan mata pelajaran yang diajar: ' . addslashes(mysqli_error($db))]);
            }
        }
    }
    elseif($action === 'edit') {
        $id = isset($_POST['id']) ? mysqli_real_escape_string($db, $_POST['id']) : '';
        
        if(@$_SESSION['admin']) {
            // Handle update for mapel (admin)
            $kode_mapel = isset($_POST['kode_mapel']) ? mysqli_real_escape_string($db, $_POST['kode_mapel']) : '';
            $mapel = isset($_POST['mapel']) ? mysqli_real_escape_string($db, $_POST['mapel']) : '';
            
            // Verify the record exists before updating
            if (!empty($id)) {
                $check_record = mysqli_query($db, "SELECT id FROM tb_mapel WHERE id = '$id'");
                if (mysqli_num_rows($check_record) == 0) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Data mata pelajaran dengan ID tersebut tidak ditemukan!']);
                    exit;
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'ID mata pelajaran tidak boleh kosong!']);
                exit;
            }
            
            $update = mysqli_query($db, "UPDATE tb_mapel SET kode_mapel = '$kode_mapel', mapel = '$mapel' WHERE id = '$id'");
            
            if($update) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Mata pelajaran berhasil diperbarui!']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui mata pelajaran: ' . addslashes(mysqli_error($db))]);
            }
        } else if(@$_SESSION['pengajar']) {
            // Handle update for mapel_ajar (pengajar)
            $mapel = isset($_POST['mapel']) ? mysqli_real_escape_string($db, $_POST['mapel']) : '';
            $kelas = isset($_POST['kelas']) ? mysqli_real_escape_string($db, $_POST['kelas']) : '';
            $ket = isset($_POST['ket']) ? mysqli_real_escape_string($db, $_POST['ket']) : '';
            
            // Verify the record exists before updating
            if (!empty($id)) {
                $check_record = mysqli_query($db, "SELECT id FROM tb_mapel_ajar WHERE id = '$id'");
                if (mysqli_num_rows($check_record) == 0) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Data mata pelajaran yang diajar dengan ID tersebut tidak ditemukan!']);
                    exit;
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'ID mata pelajaran tidak boleh kosong!']);
                exit;
            }
            
            $update = mysqli_query($db, "UPDATE tb_mapel_ajar SET id_mapel = '$mapel', id_kelas = '$kelas', keterangan = '$ket' WHERE id = '$id'");
            
            if($update) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Mata pelajaran yang diajar berhasil diperbarui!']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui mata pelajaran yang diajar: ' . addslashes(mysqli_error($db))]);
            }
        }
    }
}
// Add delete action handling
elseif(isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    
    // Verify the record exists before deleting
    $check_record = mysqli_query($db, "SELECT id FROM tb_mapel WHERE id = '$id'");
    if (mysqli_num_rows($check_record) == 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Data mata pelajaran dengan ID tersebut tidak ditemukan!']);
        exit;
    }
    
    // Delete the record from database
    $delete = mysqli_query($db, "DELETE FROM tb_mapel WHERE id = '$id'");
    
    if($delete) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Data mata pelajaran berhasil dihapus!', 'redirect' => '?page=mapel']);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus data mata pelajaran: ' . addslashes(mysqli_error($db))]);
    }
}
else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Tidak ada action yang diterima']);
}
?>