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
        // Handle insert for materi
        $judul = isset($_POST['judul']) ? mysqli_real_escape_string($db, $_POST['judul']) : '';
        $mapel = isset($_POST['id_mapel']) ? mysqli_real_escape_string($db, $_POST['id_mapel']) : '';
        $kelas = isset($_POST['id_kelas']) ? mysqli_real_escape_string($db, $_POST['id_kelas']) : '';


        // Handle file upload
        if(isset($_FILES['file']) && $_FILES['file']['name'] != '') {
            $sumber = $_FILES['file']['tmp_name'];
            $target = '../file_materi/';
            $nama_file = $_FILES['file']['name'];
            
            if(move_uploaded_file($sumber, $target.$nama_file)) {
                // Determine pembuat based on session
                if(@$_SESSION['admin']) {
                    $pembuat = 'admin';
                } else if(@$_SESSION['pengajar']) {
                    $pembuat = $_SESSION['pengajar'];
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Tidak ada sesi aktif']);
                    exit;
                }
                
                $insert = mysqli_query($db, "INSERT INTO tb_file_materi VALUES(null, '$judul', '$kelas', '$mapel', '$nama_file', now(), '$pembuat', '0')");
                
                if($insert) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'File materi berhasil ditambahkan!']);
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Gagal menambahkan file materi: ' . addslashes(mysqli_error($db))]);
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal mengupload file']);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'File harus diisi']);
        }
    }
    elseif($action === 'edit') {
        // Handle update for materi
        $id = isset($_POST['id']) ? mysqli_real_escape_string($db, $_POST['id']) : '';
        $judul = isset($_POST['judul']) ? mysqli_real_escape_string($db, $_POST['judul']) : '';
        $mapel = isset($_POST['id_mapel']) ? mysqli_real_escape_string($db, $_POST['id_mapel']) : '';
        $kelas = isset($_POST['id_kelas']) ? mysqli_real_escape_string($db, $_POST['id_kelas']) : '';
        
        
        // Verify the record exists before updating
        if (!empty($id)) {
            $check_record = mysqli_query($db, "SELECT id_materi FROM tb_file_materi WHERE id_materi = '$id'");
            if (mysqli_num_rows($check_record) == 0) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Data materi dengan ID tersebut tidak ditemukan!']);
                exit;
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID materi tidak boleh kosong!']);
            exit;
        }
        
        // Handle file upload
        if(isset($_FILES['file']) && $_FILES['file']['name'] != '') {
            $sumber = $_FILES['file']['tmp_name'];
            $target = '../file_materi/';
            $nama_file = $_FILES['file']['name'];
            
            if(move_uploaded_file($sumber, $target.$nama_file)) {
                $update = mysqli_query($db, "UPDATE tb_file_materi SET judul = '$judul', id_kelas = '$kelas', id_mapel = '$mapel', nama_file = '$nama_file' WHERE id_materi = '$id'");
                
                if($update) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'File materi berhasil diperbarui!']);
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui file materi: ' . addslashes(mysqli_error($db))]);
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal mengupload file']);
            }
        } else {
            $update = mysqli_query($db, "UPDATE tb_file_materi SET judul = '$judul', id_kelas = '$kelas', id_mapel = '$mapel' WHERE id_materi = '$id'");
            
            if($update) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'File materi berhasil diperbarui!']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui file materi: ' . addslashes(mysqli_error($db))]);
            }
        }
    }
}
// Add delete action handling
elseif(isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    
    // Get file name to delete from server
    $sql_get_file = mysqli_query($db, "SELECT nama_file FROM tb_file_materi WHERE id_materi = '$id'");
    if(mysqli_num_rows($sql_get_file) > 0) {
        $file_data = mysqli_fetch_array($sql_get_file);
        $file_name = $file_data['nama_file'];
        
        // Delete the record from database
        $delete = mysqli_query($db, "DELETE FROM tb_file_materi WHERE id_materi = '$id'");
        
        if($delete) {
            // Also delete the physical file if it exists
            if(!empty($file_name)) {
                $file_path = __DIR__ . '/../file_materi/' . $file_name;
                if(file_exists($file_path)) {
                    unlink($file_path); // Delete the physical file
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Data materi berhasil dihapus!', 'redirect' => '?page=materi']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus data materi: ' . addslashes(mysqli_error($db))]);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Data materi tidak ditemukan!']);
    }
}
else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Tidak ada action yang diterima']);
}
?>