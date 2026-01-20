<?php
@session_start();

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

// Process actions
if(isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if($action === 'tambah') {
        // Handle insert for berita
        $judul = mysqli_real_escape_string($db, $_POST['judul']);
        $isi = mysqli_real_escape_string($db, $_POST['isi']);
        $status = mysqli_real_escape_string($db, $_POST['status']);
        
        // Determine penerbit based on session
        if(@$_SESSION['admin']) {
            $penerbit = 'admin';
        } else if(@$_SESSION['pengajar']) {
            $penerbit = $_SESSION['pengajar'];
        } else {
            echo json_encode(['success' => false, 'message' => 'Tidak ada sesi aktif']);
            exit;
        }

        $insert = mysqli_query($db, "INSERT INTO tb_berita VALUES(null, '$judul', '$isi', now(), '$penerbit', '$status')");
        
        if($insert) {
            echo json_encode(['success' => true, 'message' => 'Berita berhasil ditambahkan!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan berita']);
        }
    }
    elseif($action === 'edit') {
        // Handle update for berita
        $id = mysqli_real_escape_string($db, $_POST['id']);
        $judul = mysqli_real_escape_string($db, $_POST['judul']);
        $isi = mysqli_real_escape_string($db, $_POST['isi']);
        $status = mysqli_real_escape_string($db, $_POST['status']);
        
        $update = mysqli_query($db, "UPDATE tb_berita SET judul = '$judul', isi = '$isi', status = '$status' WHERE id_berita = '$id'");
        
        if($update) {
            echo json_encode(['success' => true, 'message' => 'Berita berhasil diperbarui!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui berita']);
        }
    }
}
?>