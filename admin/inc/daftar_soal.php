<?php
require_once 'sweetalert_helper.php';
if(@$_SESSION['admin']) { ?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <a href="?page=quiz" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Kembali ke Quiz
            </a>
        </div>
        <h4 class="mb-0">
            <i class="fas fa-list text-primary"></i> Daftar Soal
        </h4>
        <p class="text-muted mb-0">Daftar semua soal quiz</p>
    </div>
</div>

<div class="modern-card shadow-sm">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="datadaftarsoal" data-export="true">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Quiz</th>
                        <th>Mata Pelajaran</th>
                        <th>Jumlah Soal</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $quiz = mysqli_query($db, "SELECT tq.*, m.mapel FROM tb_topik_quiz tq LEFT JOIN tb_mapel m ON tq.id_mapel = m.id ORDER BY tq.id_tq DESC");
                    $no = 1;
                    while($data_quiz = mysqli_fetch_array($quiz)) {
                        $jml_soal = mysqli_num_rows(mysqli_query($db, "SELECT * FROM tb_soal_pilgan WHERE id_tq = '$data_quiz[id_tq]'")) + 
                                     mysqli_num_rows(mysqli_query($db, "SELECT * FROM tb_soal_essay WHERE id_tq = '$data_quiz[id_tq]'"));
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo !empty($data_quiz['judul']) ? $data_quiz['judul'] : '-'; ?></td>
                        <td><?php echo !empty($data_quiz['mapel']) ? $data_quiz['mapel'] : '-'; ?></td>
                        <td><span class="badge bg-primary"><?php echo $jml_soal; ?> Soal</span></td>
                        <td>
                            <a href="?page=quiz&action=buatsoal&id=<?php echo $data_quiz['id_tq']; ?>" class="btn btn-success btn-sm me-1">
                                <i class="fas fa-plus"></i> Tambah Soal
                            </a>
                            <a href="?page=quiz&action=lihatsoal&id=<?php echo $data_quiz['id_tq']; ?>" class="btn btn-info btn-sm me-1">
                                <i class="fas fa-list"></i> Lihat Soal
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php } ?>