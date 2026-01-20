<?php
@session_start();
include "koneksi.php";
require_once 'sweetalert_helper.php';


// Proses form submit
if(isset($_POST['simpan'])) {
    $nama_sekolah = mysqli_real_escape_string($db, $_POST['nama_sekolah']);
    $alamat_sekolah = mysqli_real_escape_string($db, $_POST['alamat_sekolah']);
    $telp_sekolah = mysqli_real_escape_string($db, $_POST['telp_sekolah']);
    $email_sekolah = mysqli_real_escape_string($db, $_POST['email_sekolah']);
    $tahun_ajaran = mysqli_real_escape_string($db, $_POST['tahun_ajaran']);
    $nama_kepala = mysqli_real_escape_string($db, $_POST['nama_kepala']);
    $nip_kepala = mysqli_real_escape_string($db, $_POST['nip_kepala']);
    
    // Handle logo upload
    $logo_sekolah = '';
    if(isset($_FILES['logo_sekolah']) && $_FILES['logo_sekolah']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['logo_sekolah']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            $newname = 'logo_' . time() . '.' . $ext;
            $upload_path = '../style/assets/img/' . $newname;
            
            if(move_uploaded_file($_FILES['logo_sekolah']['tmp_name'], $upload_path)) {
                $logo_sekolah = $newname;
            }
        }
    }
    
    // Cek apakah data sudah ada
    $cek = mysqli_query($db, "SELECT * FROM tb_pengaturan WHERE id_pengaturan = 1");
    if(mysqli_num_rows($cek) > 0) {
        // Update
        if(!empty($logo_sekolah)) {
            $updateQuery = mysqli_query($db, "UPDATE tb_pengaturan SET 
                nama_sekolah = '$nama_sekolah', 
                alamat_sekolah = '$alamat_sekolah', 
                telp_sekolah = '$telp_sekolah', 
                email_sekolah = '$email_sekolah', 
                tahun_ajaran = '$tahun_ajaran', 
                logo_sekolah = '$logo_sekolah', 
                nama_kepala_sekolah = '$nama_kepala', 
                nip_kepala = '$nip_kepala' 
                WHERE id_pengaturan = 1");
                
            if($updateQuery) {
                echo "<script>autoSuccess('Pengaturan sekolah berhasil disimpan!'); setTimeout(function(){ window.location='?page=pengaturan'; }, 1500);</script>";
            } else {
                echo "<script>autoError('Gagal menyimpan pengaturan sekolah');</script>";
            }
        } else {
            $updateQuery = mysqli_query($db, "UPDATE tb_pengaturan SET 
                nama_sekolah = '$nama_sekolah', 
                alamat_sekolah = '$alamat_sekolah', 
                telp_sekolah = '$telp_sekolah', 
                email_sekolah = '$email_sekolah', 
                tahun_ajaran = '$tahun_ajaran', 
                nama_kepala_sekolah = '$nama_kepala', 
                nip_kepala = '$nip_kepala' 
                WHERE id_pengaturan = 1");
                
            if($updateQuery) {
                echo "<script>autoSuccess('Pengaturan sekolah berhasil diperbarui!'); setTimeout(function(){ window.location='?page=pengaturan'; }, 1500);</script>";
            } else {
                echo "<script>autoError('Gagal memperbarui pengaturan sekolah');</script>";
            }
        }
    } else {
        // Insert
        $insertQuery = mysqli_query($db, "INSERT INTO tb_pengaturan SET 
            nama_sekolah = '$nama_sekolah', 
            alamat_sekolah = '$alamat_sekolah', 
            telp_sekolah = '$telp_sekolah', 
            email_sekolah = '$email_sekolah', 
            tahun_ajaran = '$tahun_ajaran', 
            logo_sekolah = '$logo_sekolah', 
            nama_kepala_sekolah = '$nama_kepala', 
            nip_kepala = '$nip_kepala'");
            
        if($insertQuery) {
            echo "<script>autoSuccess('Pengaturan sekolah berhasil disimpan!'); setTimeout(function(){ window.location='?page=pengaturan'; }, 1500);</script>";
        } else {
            echo "<script>autoError('Gagal menyimpan pengaturan sekolah');</script>";
        }
    }
}

// Ambil data pengaturan
$pengaturan = [];
$sql = mysqli_query($db, "SELECT * FROM tb_pengaturan WHERE id_pengaturan = 1");
if(mysqli_num_rows($sql) > 0) {
    $pengaturan = mysqli_fetch_assoc($sql);
}
?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Pengaturan Sekolah</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-cog"></i> Pengaturan Identitas Sekolah
            </div>
            <div class="panel-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Sekolah *</label>
                                <input type="text" name="nama_sekolah" class="form-control" 
                                       value="<?php echo $pengaturan['nama_sekolah'] ?? ''; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Alamat Sekolah *</label>
                                <textarea name="alamat_sekolah" class="form-control" rows="3" required><?php echo $pengaturan['alamat_sekolah'] ?? ''; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Nomor Telepon</label>
                                <input type="text" name="telp_sekolah" class="form-control" 
                                       value="<?php echo $pengaturan['telp_sekolah'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Email Sekolah</label>
                                <input type="email" name="email_sekolah" class="form-control" 
                                       value="<?php echo $pengaturan['email_sekolah'] ?? ''; ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tahun Ajaran *</label>
                                <input type="text" name="tahun_ajaran" class="form-control" 
                                       value="<?php echo $pengaturan['tahun_ajaran'] ?? date('Y') . '/' . (date('Y') + 1); ?>" 
                                       placeholder="Contoh: 2024/2025" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Nama Kepala Sekolah</label>
                                <input type="text" name="nama_kepala" class="form-control" 
                                       value="<?php echo $pengaturan['nama_kepala_sekolah'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>NIP Kepala Sekolah</label>
                                <input type="text" name="nip_kepala" class="form-control" 
                                       value="<?php echo $pengaturan['nip_kepala'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Logo Sekolah</label>
                                <input type="file" name="logo_sekolah" class="form-control" accept="image/*">
                                <small class="text-muted">Format: JPG, PNG, GIF. Max 2MB</small>
                                
                                <?php if(!empty($pengaturan['logo_sekolah'])): ?>
                                <div style="margin-top: 10px;">
                                    <img src="../style/assets/img/<?php echo $pengaturan['logo_sekolah']; ?>" 
                                         style="max-width: 150px; max-height: 100px; border: 1px solid #ddd; padding: 5px;">
                                    <br>
                                    <small>Logo saat ini: <?php echo $pengaturan['logo_sekolah']; ?></small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" name="simpan" class="btn btn-success">
                            <i class="glyphicon glyphicon-save"></i> Simpan Pengaturan
                        </button>
                        <a href="?page=dashboard" class="btn btn-default">
                            <i class="glyphicon glyphicon-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-info-sign"></i> Informasi
            </div>
            <div class="panel-body">
                <p><strong>Pengaturan ini digunakan untuk:</strong></p>
                <ul>
                    <li>Header di laporan/cetak data</li>
                    <li>Identitas sekolah di dokumen</li>
                    <li>Tampilan umum aplikasi</li>
                </ul>
                
                <hr>
                
                <p><strong>Catatan:</strong></p>
                <ul>
                    <li>Tanda (*) wajib diisi</li>
                    <li>Logo akan muncul di header laporan</li>
                    <li>Tahun ajaran format: YYYY/YYYY</li>
                </ul>
            </div>
        </div>
    </div>
</div>