<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Manajemen Tugas / Quiz</h1>
    </div>
</div>

<!-- Include modal template -->
<?php include 'modal_template.php'; ?>

<?php

$id = @$_GET['id'];
$id_tq = @$_GET['id_tq'];
$no = 1;

if(@$_SESSION['admin']) {
    $sql_topik = mysqli_query($db, "SELECT * FROM tb_topik_quiz JOIN tb_kelas ON tb_topik_quiz.id_kelas = tb_kelas.id_kelas JOIN tb_mapel ON tb_topik_quiz.id_mapel = tb_mapel.id ORDER BY tgl_buat DESC") or die ($db->error);
    $pembuat = "admin";
} else if(@$_SESSION['pengajar']) {
    $sql_topik = mysqli_query($db, "SELECT * FROM tb_topik_quiz JOIN tb_kelas ON tb_topik_quiz.id_kelas = tb_kelas.id_kelas JOIN tb_mapel ON tb_topik_quiz.id_mapel = tb_mapel.id WHERE pembuat != 'admin' AND pembuat = '$_SESSION[pengajar]' ORDER BY tgl_buat DESC") or die ($db->error);
    $pembuat = @$_SESSION['pengajar'];
} 

if(@$_GET['action'] == '') { ?>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="modern-card shadow-sm">
                <div class="card-header bg-white py-4 px-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                        <div class="me-3">
                            <h5 class="card-title mb-2">
                                <i class="fas fa-tasks me-2 text-primary"></i>
                                Daftar Topik Quiz / Tugas
                            </h5>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <?php 
                                $total_quiz = mysqli_num_rows($sql_topik);
                                $active_quiz = mysqli_num_rows(mysqli_query($db, "SELECT * FROM tb_topik_quiz WHERE status = 'aktif'"));
                                $inactive_quiz = $total_quiz - $active_quiz;
                                ?>
                                <span class="badge bg-primary fs-6 py-2 px-3">
                                    <i class="fas fa-layer-group me-1"></i>
                                    Total: <?php echo $total_quiz; ?> Quiz
                                </span>
                                <span class="badge bg-success fs-6 py-2 px-3">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Aktif: <?php echo $active_quiz; ?>
                                </span>
                                <?php if($inactive_quiz > 0): ?>
                                <span class="badge bg-warning fs-6 py-2 px-3">
                                    <i class="fas fa-pause-circle me-1"></i>
                                    Non-Aktif: <?php echo $inactive_quiz; ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <button class="btn btn-primary btn-sm px-3 py-2" onclick="openModal('tambah')">
                                <i class="fas fa-plus me-1"></i> Tambah Topik
                            </button>
                            <a href="./laporan/cetak_print.php?data=topikquiz" target="_blank" class="btn btn-outline-secondary btn-sm px-3 py-2">
                                <i class="fas fa-print me-1"></i> Cetak
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="dataquiz" data-export="true">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Judul</th>
                                    <th>Kelas</th>
                                    <th>Mapel</th>
                                    <th>Tanggal Pembuatan</th>
                                    <?php
                                    if(@$_SESSION['admin']) {
                                        echo "<th>Pembuat</th>";
                                    } ?>
                                    <th>Waktu</th>
                                    <th>Info</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(mysqli_num_rows($sql_topik) > 0) {
                                while($data_topik = mysqli_fetch_array($sql_topik)) { ?>
                                    <tr>
                                        <td align="center"><?php echo $no++; ?></td>
                                        <td><?php echo $data_topik['judul']; ?></td>
                                        <td align="center"><?php echo $data_topik['nama_kelas']; ?></td>
                                        <td><?php echo $data_topik['mapel']; ?></td>
                                        <td><?php echo tgl_indo($data_topik['tgl_buat']); ?></td>
                                        <?php
                                        if(@$_SESSION['admin']) {
                                            if($data_topik['pembuat'] == 'admin') {
                                                echo "<td>Admin</td>";
                                            } else {
                                                $sql1 = mysqli_query($db, "SELECT * FROM tb_pengajar WHERE id_pengajar = '$data_topik[pembuat]'") or die($db->error);
                                                $data1 = mysqli_fetch_array($sql1);
                                                echo "<td>".$data1['nama_lengkap']."</td>";
                                            }
                                        } ?>
                                        <td><?php echo $data_topik['waktu_soal'] / 60 ." menit"; ?></td>
                                        <td><?php echo $data_topik['info']; ?></td>
                                        <td align="center"><?php echo ucfirst($data_topik['status']); ?></td>
                                        <td align="center">
                                            <div class="btn-group-vertical w-100">
                                                <button class="btn btn-warning btn-sm mb-1" onclick="openModal('edit', '<?php echo $data_topik['id_tq']; ?>')">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button class="btn btn-danger btn-sm mb-2" onclick="confirmDelete('Hati-hati saat menghapus topik quiz karena Anda akan menghapus semua data yang berhubungan dengan topik ini, termasuk data soal dan nilai. Apakah Anda tetap yakin akan menghapus topik ini?', '?page=quiz&action=hapus&id_tq=<?php echo $data_topik['id_tq']; ?>')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                                <div class="d-grid gap-1">
                                                    <a href="?page=quiz&action=buatsoal&id=<?php echo $data_topik['id_tq']; ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-plus-circle"></i> Buat Soal
                                                    </a>
                                                    <a href="?page=quiz&action=daftarsoal&id=<?php echo $data_topik['id_tq']; ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-list"></i> Daftar Soal
                                                    </a>
                                                    <a href="?page=quiz&action=pesertakoreksi&id_tq=<?php echo $data_topik['id_tq']; ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-users"></i> Peserta & Koreksi
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                            	echo '<td colspan="9" align="center">Tidak ada data</td>';
                        	} ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php
} else if(@$_GET['action'] == 'hapus') {
    mysqli_query($db, "DELETE FROM tb_topik_quiz WHERE id_tq = '$_GET[id_tq]'") or die ($db->error);
    mysqli_query($db, "DELETE FROM tb_soal_pilgan WHERE id_tq = '$_GET[id_tq]'") or die ($db->error);
    mysqli_query($db, "DELETE FROM tb_soal_essay WHERE id_tq = '$_GET[id_tq]'") or die ($db->error);
    mysqli_query($db, "DELETE FROM tb_jawaban WHERE id_tq = '$_GET[id_tq]'") or die ($db->error);
    mysqli_query($db, "DELETE FROM tb_nilai_pilgan WHERE id_tq = '$_GET[id_tq]'") or die ($db->error);
    mysqli_query($db, "DELETE FROM tb_nilai_essay WHERE id_tq = '$_GET[id_tq]'") or die ($db->error);
    echo '<!DOCTYPE html>
<html>
<head>
    <title>Processing...</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="alert alert-success text-center">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5>Success!</h5>
                <p>Quiz/Tugas berhasil dihapus!</p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Show success notification
    Swal.fire({
        icon: "success",
        title: "Berhasil!",
        text: "Quiz/Tugas berhasil dihapus!",
        timer: 1500,
        showConfirmButton: false,
        position: "top-end",
        toast: true
    });
    
    // Redirect after delay
    setTimeout(function(){
        window.location.href = "?page=quiz";
    }, 1500);
</script>
</body>
</html>';
    exit();
} else if(@$_GET['action'] == 'buatsoal') {
    $id_tq = @$_GET['id'];
    
    if(!$id_tq) {
        echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> ID Quiz tidak ditemukan!</div>';
        return;
    }
    
    // Get quiz topic info
    $quiz_info = mysqli_query($db, "SELECT tq.*, m.mapel FROM tb_topik_quiz tq LEFT JOIN tb_mapel m ON tq.id_mapel = m.id WHERE tq.id_tq = '$id_tq'");
    $data_quiz = mysqli_fetch_array($quiz_info);
    
    if(!$data_quiz) {
        echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Quiz tidak ditemukan!</div>';
        return;
    }
    
    // Count existing questions
    $count_pilgan = mysqli_num_rows(mysqli_query($db, "SELECT * FROM tb_soal_pilgan WHERE id_tq = '$id_tq'"));
    $count_essay = mysqli_num_rows(mysqli_query($db, "SELECT * FROM tb_soal_essay WHERE id_tq = '$id_tq'"));
    
    ?>
    
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="mb-0">
                <i class="fas fa-plus-circle text-primary"></i> Tambah Soal untuk "<?php echo $data_quiz['judul']; ?>"
            </h4>
            <p class="text-muted mb-0">
                <?php echo !empty($data_quiz['mapel']) ? $data_quiz['mapel'] : 'Umum'; ?> | 
                Total Soal: <?php echo ($count_pilgan + $count_essay); ?> (PG: <?php echo $count_pilgan; ?>, Essay: <?php echo $count_essay; ?>)
            </p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="modern-card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-list-ol text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Soal Pilihan Ganda</h5>
                    <p class="text-muted">Buat soal dengan pilihan jawaban A-E</p>
                    <div class="mb-3">
                        <span class="badge bg-primary"><?php echo $count_pilgan; ?> soal tersedia</span>
                    </div>
                    <a href="?page=quiz&action=tambahpilgan&id=<?php echo $id_tq; ?>" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i> Tambah Soal PG
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="modern-card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-edit text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Soal Essay</h5>
                    <p class="text-muted">Buat soal dengan jawaban terbuka</p>
                    <div class="mb-3">
                        <span class="badge bg-success"><?php echo $count_essay; ?> soal tersedia</span>
                    </div>
                    <a href="?page=quiz&action=tambahessay&id=<?php echo $id_tq; ?>" class="btn btn-success w-100">
                        <i class="fas fa-plus me-2"></i> Tambah Soal Essay
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="?page=quiz" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Quiz
        </a>
        <a href="?page=quiz&action=lihatsoal&id=<?php echo $id_tq; ?>" class="btn btn-info ms-2">
            <i class="fas fa-list me-2"></i> Lihat Semua Soal
        </a>
    </div>
    
    <?php
} else if(@$_GET['action'] == 'daftarsoal') {
    
} else if(@$_GET['action'] == 'pesertakoreksi') {
    
} else if(@$_GET['action'] == 'koreksi') {
    
} else if(@$_GET['action'] == 'hapuspeserta') {
    mysqli_query($db, "DELETE FROM tb_jawaban WHERE id_tq = '$_GET[id_tq]' AND id_siswa = '$_GET[id_siswa]'") or die ($db->error);
    mysqli_query($db, "DELETE FROM tb_nilai_pilgan WHERE id_tq = '$_GET[id_tq]' AND id_siswa = '$_GET[id_siswa]'") or die ($db->error);
    mysqli_query($db, "DELETE FROM tb_nilai_essay WHERE id_tq = '$_GET[id_tq]' AND id_siswa = '$_GET[id_siswa]'") or die ($db->error);
    echo '<!DOCTYPE html>
<html>
<head>
    <title>Processing...</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="alert alert-success text-center">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5>Success!</h5>
                <p>Peserta berhasil dihapus!</p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Show success notification
    Swal.fire({
        icon: "success",
        title: "Berhasil!",
        text: "Peserta berhasil dihapus!",
        timer: 1500,
        showConfirmButton: false,
        position: "top-end",
        toast: true
    });
    
    // Redirect after delay
    setTimeout(function(){
        window.location.href = "?page=quiz&action=pesertakoreksi&id_tq=' . @$_GET['id_tq'] . '";
    }, 1500);
</script>
</body>
</html>';
    exit();
} ?>

<script>
// Set the entity process file for the modal
var entityProcessFile = 'quiz';

// Override form content loader for quiz
function loadFormContent(action, id) {
    console.log('quiz loadFormContent called with:', action, id);
    let content = '';
    
    if(action === 'tambah') {
        content = `
            <input type="hidden" name="action" value="tambah">
            <div class="form-group">
                <label>Judul *</label>
                <input type="text" id="judul" name="judul" class="form-control" placeholder="Ex: Ulangan Harian 1" required />
            </div>
            <div class="form-group">
                <label>Kelas *</label>                            
                <div class="wadah_kelas">
                    <div id="ke-1">
                    <select name="kelas" class="form-control x">
                        <option value="">- Pilih -</option>
                        <?php
                        $sql_kelas = mysqli_query($db, "SELECT * FROM tb_kelas") or die ($db->error);
                        while($data_kelas = mysqli_fetch_array($sql_kelas)) {
                            echo '<option value="'.$data_kelas['id_kelas'].'">'.$data_kelas['nama_kelas'].'</option>';
                        } ?>
                    </select>
                    </div>
                    <a style="margin:3px 0 4px 0;" class="tambah_kelas btn btn-primary btn-xs">Tambah Kelas Lain</a> <small><i>(Klik button untuk menambahkan kelas lain, max. 10 kelas)</i></small>
                </div>
                <a href="" style="margin:2px 0; display:none;" class="del-kls btn btn btn-danger btn-xs">Delete Kelas Lain</a>
            </div>
            <div class="form-group">
                <label>Mapel *</label>
                <select id="mapel" name="mapel" class="form-control" required>
                    <option value="">- Pilih -</option>
                    <?php
                    $sql_mapel = mysqli_query($db, "SELECT * FROM tb_mapel") or die ($db->error);
                    while($data_mapel = mysqli_fetch_array($sql_mapel)) {
                        echo '<option value="'.$data_mapel['id'].'">'.$data_mapel['mapel'].'</option>';
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Pembuatan *</label>
                <input type="date" id="tgl_buat" name="tgl_buat" value="<?php echo date('Y-m-d'); ?>" class="form-control" required />
            </div>
            <div class="form-group">
                <label>Waktu Soal * <sub>(dalam menit)</sub></label>
                <input type="text" id="waktu_soal" name="waktu_soal" class="form-control" required />
            </div>
            <div class="form-group">
                <label>Info</label>
                <textarea name="info" id="info" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="aktif">Aktif</option>
                    <option value="tidak aktif">Tidak Aktif</option>
                </select>
            </div>
        `;
    } else if(action === 'edit') {
        content = `
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="${id}">
            <div class="form-group">
                <label>Judul *</label>
                <input type="text" name="judul" class="form-control" required id="edit_judul" />
            </div>
            <div class="form-group">
                <label>Kelas *</label>
                <select name="kelas" class="form-control" required id="edit_kelas">
                    <option value="">- Pilih -</option>
                    <?php
                    $sql_kelas = mysqli_query($db, "SELECT * FROM tb_kelas") or die ($db->error);
                    while($data_kelas = mysqli_fetch_array($sql_kelas)) {
                        echo '<option value="'.$data_kelas['id_kelas'].'">'.$data_kelas['nama_kelas'].'</option>';
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Mapel *</label>
                <select name="mapel" class="form-control" required id="edit_mapel">
                    <option value="">- Pilih -</option>
                    <?php
                    $sql_mapel = mysqli_query($db, "SELECT * FROM tb_mapel") or die ($db->error);
                    while($data_mapel = mysqli_fetch_array($sql_mapel)) {
                        echo '<option value="'.$data_mapel['id'].'">'.$data_mapel['mapel'].'</option>';
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Pembuatan *</label>
                <input type="date" name="tgl_buat" class="form-control" required id="edit_tgl_buat" />
            </div>
            <div class="form-group">
                <label>Waktu Soal * <sub>(dalam menit)</sub></label>
                <input type="text" name="waktu_soal" class="form-control" required id="edit_waktu_soal" />
            </div>
            <div class="form-group">
                <label>Info</label>
                <textarea name="info" class="form-control" rows="3" id="edit_info"></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" id="edit_status">
                    <option value="aktif">Aktif</option>
                    <option value="tidak aktif">Tidak Aktif</option>
                </select>
            </div>
        `;
        
        // Load data for edit
        if(id) {
            $.get('inc/get_quiz_data.php?id=' + id, function(data) {
                $('#edit_judul').val(data.judul);
                $('#edit_kelas').val(data.id_kelas);
                $('#edit_mapel').val(data.id_mapel);
                $('#edit_tgl_buat').val(data.tgl_buat);
                $('#edit_waktu_soal').val(data.waktu_soal / 60);
                $('#edit_info').val(data.info);
                $('#edit_status').val(data.status);
            });
        }
    }
    
    console.log('Setting quiz form content, length:', content.length);
    // Use jQuery if available, fallback to vanilla JS
    if(typeof $ !== 'undefined' && $('#formContent').length) {
        $('#formContent').html(content);
    } else {
        document.getElementById('formContent').innerHTML = content;
    }
    
    // Initialize class selector for tambah action
    if(action === 'tambah') {
        var ke = 1;
        var x = 1;
        var isikelas = '';
        <?php
        $sql_kelas = mysqli_query($db, "SELECT * FROM tb_kelas") or die ($db->error);
        while($data_kelas = mysqli_fetch_array($sql_kelas)) {
            echo 'isikelas += \'<option value="'.$data_kelas['id_kelas'].'">'.$data_kelas['nama_kelas'].'</option>\';';
        }
        ?>
        
        $(".tambah_kelas").click(function(e){
            e.preventDefault();
            if(x < 10){
                x++;
                ke++;
                $(".wadah_kelas").append('<div id="ke-'+ke+'"><select style="margin-bottom:2px;" name="kelas" class="form-control x"><option value="">- Pilih -</option>' + isikelas + '</select> <div>');
                $(".del-kls").fadeIn();
            }
        });
        
        $(".wadah_kelas").on("click",".del", function(e){
            e.preventDefault(); $(this).parent('div').remove(); x--;
        });
    }
}
</script>