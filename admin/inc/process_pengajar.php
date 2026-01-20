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
        // Handle insert for pengajar
        $nip = isset($_POST['nip']) ? mysqli_real_escape_string($db, $_POST['nip']) : '';
        $nama_lengkap = isset($_POST['nama_lengkap']) ? mysqli_real_escape_string($db, $_POST['nama_lengkap']) : '';
        $tempat_lahir = isset($_POST['tempat_lahir']) ? mysqli_real_escape_string($db, $_POST['tempat_lahir']) : '';
        $tgl_lahir = isset($_POST['tgl_lahir']) ? mysqli_real_escape_string($db, $_POST['tgl_lahir']) : '';
        $jenis_kelamin = isset($_POST['jenis_kelamin']) ? mysqli_real_escape_string($db, $_POST['jenis_kelamin']) : '';
        $agama = isset($_POST['agama']) ? mysqli_real_escape_string($db, $_POST['agama']) : '';
        $no_telp = isset($_POST['no_telp']) ? mysqli_real_escape_string($db, $_POST['no_telp']) : '';
        $email = isset($_POST['email']) ? mysqli_real_escape_string($db, $_POST['email']) : '';
        $alamat = isset($_POST['alamat']) ? mysqli_real_escape_string($db, $_POST['alamat']) : '';
        $jabatan = isset($_POST['jabatan']) ? mysqli_real_escape_string($db, $_POST['jabatan']) : '';
        $web = isset($_POST['web']) ? mysqli_real_escape_string($db, $_POST['web']) : '';
        $username = isset($_POST['username']) ? mysqli_real_escape_string($db, $_POST['username']) : '';
        $password = isset($_POST['password']) ? mysqli_real_escape_string($db, $_POST['password']) : '';
        $status = isset($_POST['status']) ? mysqli_real_escape_string($db, $_POST['status']) : '';

        // Handle file upload
        $nama_gambar = 'anonim.png';
        if(isset($_FILES['gambar']) && $_FILES['gambar']['name'] != '') {
            $sumber = $_FILES['gambar']['tmp_name'];
            $target = 'img/foto_pengajar/';
            $nama_gambar = $_FILES['gambar']['name'];
            
            if(!move_uploaded_file($sumber, $target.$nama_gambar)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal mengupload foto']);
                exit;
            }
        }

        $insert = mysqli_query($db, "INSERT INTO tb_pengajar VALUES(null, '$nip', '$nama_lengkap', '$tempat_lahir', '$tgl_lahir', '$jenis_kelamin', '$agama', '$no_telp', '$email', '$alamat', '$jabatan', '$nama_gambar', '$web', '$username', md5('$password'), '$password', '$status')");
        
        if($insert) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Data pengajar berhasil ditambahkan!']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan data pengajar: ' . addslashes(mysqli_error($db))]);
        }
    }
    elseif($action === 'edit') {
        // Handle update for pengajar
        $id = isset($_POST['id']) ? mysqli_real_escape_string($db, $_POST['id']) : '';
        $nip = isset($_POST['nip']) ? mysqli_real_escape_string($db, $_POST['nip']) : '';
        $nama_lengkap = isset($_POST['nama_lengkap']) ? mysqli_real_escape_string($db, $_POST['nama_lengkap']) : '';
        $tempat_lahir = isset($_POST['tempat_lahir']) ? mysqli_real_escape_string($db, $_POST['tempat_lahir']) : '';
        $tgl_lahir = isset($_POST['tgl_lahir']) ? mysqli_real_escape_string($db, $_POST['tgl_lahir']) : '';
        $jenis_kelamin = isset($_POST['jenis_kelamin']) ? mysqli_real_escape_string($db, $_POST['jenis_kelamin']) : '';
        $agama = isset($_POST['agama']) ? mysqli_real_escape_string($db, $_POST['agama']) : '';
        $no_telp = isset($_POST['no_telp']) ? mysqli_real_escape_string($db, $_POST['no_telp']) : '';
        $email = isset($_POST['email']) ? mysqli_real_escape_string($db, $_POST['email']) : '';
        $alamat = isset($_POST['alamat']) ? mysqli_real_escape_string($db, $_POST['alamat']) : '';
        $jabatan = isset($_POST['jabatan']) ? mysqli_real_escape_string($db, $_POST['jabatan']) : '';
        $web = isset($_POST['web']) ? mysqli_real_escape_string($db, $_POST['web']) : '';
        $username = isset($_POST['username']) ? mysqli_real_escape_string($db, $_POST['username']) : '';
        $password = isset($_POST['password']) ? mysqli_real_escape_string($db, $_POST['password']) : '';
        $status = isset($_POST['status']) ? mysqli_real_escape_string($db, $_POST['status']) : '';
        
        // Verify the record exists before updating
        if (!empty($id)) {
            $check_record = mysqli_query($db, "SELECT id_pengajar FROM tb_pengajar WHERE id_pengajar = '$id'");
            if (mysqli_num_rows($check_record) == 0) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Data pengajar dengan ID tersebut tidak ditemukan!']);
                exit;
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID pengajar tidak boleh kosong!']);
            exit;
        }

        // Handle file upload
        if(isset($_FILES['gambar']) && $_FILES['gambar']['name'] != '') {
            $sumber = $_FILES['gambar']['tmp_name'];
            $target = 'img/foto_pengajar/';
            $nama_gambar = $_FILES['gambar']['name'];
            
            if(move_uploaded_file($sumber, $target.$nama_gambar)) {
                $update = mysqli_query($db, "UPDATE tb_pengajar SET nip = '$nip', nama_lengkap = '$nama_lengkap', tempat_lahir = '$tempat_lahir', tgl_lahir = '$tgl_lahir', jenis_kelamin = '$jenis_kelamin', agama = '$agama', no_telp = '$no_telp', email = '$email', alamat = '$alamat', jabatan = '$jabatan', foto = '$nama_gambar', web = '$web', username = '$username', password = md5('$password'), pass = '$password', status = '$status' WHERE id_pengajar = '$id'");
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal mengupload foto']);
                exit;
            }
        } else {
            if(!empty($password)) {
                $update = mysqli_query($db, "UPDATE tb_pengajar SET nip = '$nip', nama_lengkap = '$nama_lengkap', tempat_lahir = '$tempat_lahir', tgl_lahir = '$tgl_lahir', jenis_kelamin = '$jenis_kelamin', agama = '$agama', no_telp = '$no_telp', email = '$email', alamat = '$alamat', jabatan = '$jabatan', web = '$web', username = '$username', password = md5('$password'), pass = '$password', status = '$status' WHERE id_pengajar = '$id'");
            } else {
                $update = mysqli_query($db, "UPDATE tb_pengajar SET nip = '$nip', nama_lengkap = '$nama_lengkap', tempat_lahir = '$tempat_lahir', tgl_lahir = '$tgl_lahir', jenis_kelamin = '$jenis_kelamin', agama = '$agama', no_telp = '$no_telp', email = '$email', alamat = '$alamat', jabatan = '$jabatan', web = '$web', username = '$username', status = '$status' WHERE id_pengajar = '$id'");
            }
        }
        
        if($update) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Data pengajar berhasil diperbarui!']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui data pengajar: ' . addslashes(mysqli_error($db))]);
        }
    }
}
else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Tidak ada action yang diterima']);
}
?>