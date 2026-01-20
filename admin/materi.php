<?php
@session_start();
include "koneksi.php";

if(!@$_SESSION['admin'] && !@$_SESSION['pengajar']) {
    echo "<script>window.location='index.php';</script>";
} else {
    // Handle delete action
    if(isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
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
                    $file_path = __DIR__ . '/file_materi/' . $file_name;
                    if(file_exists($file_path)) {
                        unlink($file_path); // Delete the physical file
                    }
                }
                
                // Show success message and redirect
                echo "<script>alert('Data materi berhasil dihapus!'); window.location='?page=materi';</script>";
            } else {
                echo "<script>alert('Gagal menghapus data materi: " . addslashes(mysqli_error($db)) . "'); window.location='?page=materi';</script>";
            }
        } else {
            echo "<script>alert('Data materi tidak ditemukan!'); window.location='?page=materi';</script>";
        }
    } else {
        // Load materi modal
        include "inc/materi_modal.php";
    }
}
?>