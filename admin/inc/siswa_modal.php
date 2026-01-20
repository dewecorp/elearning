<?php
@session_start();

require_once 'sweetalert_helper.php';

if(@$_GET['action'] == 'hapus') {
    $id = @$_GET['id'];
    mysqli_query($db, "DELETE FROM tb_siswa WHERE id_siswa = '$id'") or die ($db->error);
    echo "<script>autoSuccess('Data berhasil dihapus!'); setTimeout(function(){ window.location='?page=siswa'; }, 1500);</script>";
} else if(@$_GET['action'] == 'detail') {
    // Show student details - similar to original
    $sql_siswa_detail = mysqli_query($db, "SELECT * FROM tb_siswa JOIN tb_kelas ON tb_siswa.id_kelas = tb_kelas.id_kelas WHERE tb_siswa.id_siswa = '$_GET[IDsiswa]'") or die ($db->error);
    $data_siswa_detail = mysqli_fetch_array($sql_siswa_detail);
    ?>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="modern-card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2 text-primary"></i>
                            Detail Data Siswa
                        </h5>
                        <a href="?page=siswa" class="btn btn-warning btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <td width="30%" class="fw-bold">NIS</td>
                                <td width="5%">:</td>
                                <td><?php echo $data_siswa_detail['nis']; ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nama Lengkap</td>
                                <td>:</td>
                                <td><?php echo $data_siswa_detail['nama_lengkap']; ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jenis Kelamin</td>
                                <td>:</td>
                                <td><?php if($data_siswa_detail['jenis_kelamin'] == 'L') { echo "Laki-laki"; } else { echo "Perempuan"; } ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Alamat</td>
                                <td>:</td>
                                <td><?php echo $data_siswa_detail['alamat']; ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kelas</td>
                                <td>:</td>
                                <td><?php echo $data_siswa_detail['nama_kelas']; ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Username</td>
                                <td>:</td>
                                <td><?php echo $data_siswa_detail['username']; ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status</td>
                                <td>:</td>
                                <td><?php echo ucfirst($data_siswa_detail['status']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} else if(@$_GET['action'] == 'nonaktifkan') {
    $id = @$_GET['id'];
    mysqli_query($db, "UPDATE tb_siswa SET status = 'tidak aktif' WHERE id_siswa = '$id'") or die ($db->error);
    echo "<script>autoSuccess('Siswa berhasil dinonaktifkan!'); setTimeout(function(){ window.location='?page=siswa'; }, 1500);</script>";
} else if(@$_SESSION['admin']) { 

// Get class filter if provided
$IDkelas = @$_GET['IDkelas'];
$no = 1;

// Build query based on class filter
if(empty($IDkelas)) {
    $sql_siswa = mysqli_query($db, "SELECT * FROM tb_siswa JOIN tb_kelas ON tb_siswa.id_kelas = tb_kelas.id_kelas WHERE tb_siswa.status = 'aktif' ORDER BY tb_siswa.nama_lengkap") or die ($db->error);
} else {
    $sql_siswa = mysqli_query($db, "SELECT * FROM tb_siswa JOIN tb_kelas ON tb_siswa.id_kelas = tb_kelas.id_kelas WHERE tb_siswa.status = 'aktif' AND tb_siswa.id_kelas = '$IDkelas' ORDER BY tb_siswa.nama_lengkap") or die ($db->error);
}

?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="modern-card shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2 text-primary"></i>
                        <?php
                        if(empty($IDkelas)) {
                            echo 'Data Siswa yang Aktif';
                        } else {
                            $kelas_info = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM tb_kelas WHERE id_kelas = '$IDkelas'"));
                            echo "Data Siswa Per Kelas " . $kelas_info['nama_kelas'] . " yang Aktif";
                        }
                        ?>
                    </h5>
                    <div>
                        <?php if(empty($IDkelas)): ?>
                            <a href="./laporan/cetak_print.php?data=siswa" target="_blank" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-print"></i> Cetak Data Siswa
                            </a>
                        <?php else: ?>
                            <a href="?page=kelas" class="btn btn-warning btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="datasiswa" data-export="true">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(mysqli_num_rows($sql_siswa) > 0) {
                                while($data_siswa = mysqli_fetch_array($sql_siswa)) { ?>
                                    <tr>
                                        <td align="center"><?php echo $no++; ?></td>
                                        <td><?php echo $data_siswa['nis']; ?></td>
                                        <td><?php echo $data_siswa['nama_lengkap']; ?></td>
                                        <td><?php echo $data_siswa['jenis_kelamin']; ?></td>
                                        <td><?php echo $data_siswa['alamat']; ?></td>
                                        <td align="center"><?php echo $data_siswa['nama_kelas']; ?></td>
                                        <td><?php echo ucfirst($data_siswa['status']); ?></td>
                                        <td align="center">
                                            <a href="?page=siswa&action=nonaktifkan&id=<?php echo $data_siswa['id_siswa']; ?>" class="btn btn-warning btn-sm me-1">
                                                <i class="fas fa-pause"></i> Non Aktif
                                            </a>
                                            <button class="btn btn-danger btn-sm me-1" onclick="confirmDelete('Yakin akan menghapus data?', '?page=siswa&action=hapus&id=<?php echo $data_siswa['id_siswa']; ?>')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                            <a href="?page=siswa&action=detail&IDsiswa=<?php echo $data_siswa['id_siswa']; ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="8" align="center" class="text-muted py-5">
                                        <i class="fas fa-users-slash" style="font-size: 3rem;"></i>
                                        <h4 class="mt-3">Tidak Ada Data Siswa</h4>
                                        <p>Data siswa tidak ditemukan</p>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(message, deleteUrl) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = deleteUrl;
        }
    });
}
</script>

<?php } ?>