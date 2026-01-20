<?php
require_once 'sweetalert_helper.php';
if(@$_SESSION['admin']) { 

// Get quiz topic ID if provided
$id_tq = @$_GET['id_tq'];

// Build query based on whether specific quiz is selected
if($id_tq) {
    // Get specific quiz info
    $quiz_query = mysqli_query($db, "SELECT tq.*, m.mapel FROM tb_topik_quiz tq LEFT JOIN tb_mapel m ON tq.id_mapel = m.id WHERE tq.id_tq = '$id_tq'");
    $quiz_data = mysqli_fetch_array($quiz_query);
    
    // Get students who have answered this quiz
    $students_query = mysqli_query($db, "
        SELECT DISTINCT s.*, 
               np.benar, np.salah, np.tidak_dikerjakan, np.presentase,
               ne.nilai as nilai_essay
        FROM tb_siswa s
        LEFT JOIN tb_nilai_pilgan np ON s.id_siswa = np.id_siswa AND np.id_tq = '$id_tq'
        LEFT JOIN tb_nilai_essay ne ON s.id_siswa = ne.id_siswa AND ne.id_tq = '$id_tq'
        WHERE s.status = 'aktif'
        ORDER BY s.nama_lengkap ASC
    ");
} else {
    // Get all quizzes with student participation
    $quizzes_query = mysqli_query($db, "
        SELECT DISTINCT tq.id_tq, tq.judul, m.mapel,
               COUNT(DISTINCT np.id_siswa) as jml_pg,
               COUNT(DISTINCT ne.id_siswa) as jml_essay
        FROM tb_topik_quiz tq
        LEFT JOIN tb_mapel m ON tq.id_mapel = m.id
        LEFT JOIN tb_nilai_pilgan np ON tq.id_tq = np.id_tq
        LEFT JOIN tb_nilai_essay ne ON tq.id_tq = ne.id_tq
        GROUP BY tq.id_tq
        ORDER BY tq.tgl_buat DESC
    ");
}

?>

<div class="row mb-4">
    <div class="col-md-12">
        <h4 class="mb-0">
            <i class="fas fa-users text-primary"></i> <?php echo $id_tq ? 'Peserta Koreksi - ' . $quiz_data['judul'] : 'Daftar Peserta Koreksi'; ?>
        </h4>
        <p class="text-muted mb-0">
            <?php if($id_tq): ?>
                <?php echo !empty($quiz_data['mapel']) ? $quiz_data['mapel'] : 'Umum'; ?> | 
                <?php echo date('d-m-Y', strtotime($quiz_data['tgl_buat'])); ?>
            <?php else: ?>
                Kelola peserta yang telah mengikuti quiz dan perlu dikoreksi
            <?php endif; ?>
        </p>
    </div>
</div>

<div class="modern-card shadow-sm">
    <div class="card-body p-4">
        <?php if($id_tq): ?>
            <!-- Student List for Specific Quiz -->
            <?php if(mysqli_num_rows($students_query) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="datapartisipan" data-export="true">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>PG Benar</th>
                                <th>Nilai Essay</th>
                                <th>Status</th>
                                <th width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($student = mysqli_fetch_array($students_query)): 
                            $status_class = (!empty($student['benar']) || !empty($student['nilai_essay'])) ? 'bg-success' : 'bg-warning';
                            $status_text = (!empty($student['benar']) || !empty($student['nilai_essay'])) ? 'Sudah Dinilai' : 'Belum Dinilai';
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $student['nis']; ?></td>
                                <td><?php echo $student['nama_lengkap']; ?></td>
                                <td><?php echo $student['id_kelas']; ?></td>
                                <td>
                                    <?php if(!empty($student['benar'])): ?>
                                        <span class="badge bg-primary"><?php echo $student['benar']; ?> benar</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($student['nilai_essay'])): ?>
                                        <span class="badge bg-info"><?php echo $student['nilai_essay']; ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                <td>
                                    <a href="?page=quiz&action=koreksi&id_tq=<?php echo $id_tq; ?>&id_siswa=<?php echo $student['id_siswa']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Koreksi
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Peserta</h4>
                    <p class="text-muted">Belum ada siswa yang mengikuti quiz ini.</p>
                </div>
            <?php endif; ?>
            
            <div class="mt-4">
                <a href="?page=quiz&action=pesertakoreksi" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Quiz
                </a>
            </div>
            
        <?php else: ?>
            <!-- Quiz List -->
            <?php if(mysqli_num_rows($quizzes_query) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="datapesertakuis" data-export="true">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Judul Quiz</th>
                                <th>Mata Pelajaran</th>
                                <th>Peserta PG</th>
                                <th>Peserta Essay</th>
                                <th>Total Peserta</th>
                                <th width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($quiz = mysqli_fetch_array($quizzes_query)): 
                            $total_peserta = $quiz['jml_pg'] + $quiz['jml_essay'];
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $quiz['judul']; ?></td>
                                <td><?php echo !empty($quiz['mapel']) ? $quiz['mapel'] : '-'; ?></td>
                                <td><span class="badge bg-primary"><?php echo $quiz['jml_pg']; ?> siswa</span></td>
                                <td><span class="badge bg-info"><?php echo $quiz['jml_essay']; ?> siswa</span></td>
                                <td><span class="badge bg-success"><?php echo $total_peserta; ?> siswa</span></td>
                                <td>
                                    <a href="?page=quiz&action=pesertakoreksi&id_tq=<?php echo $quiz['id_tq']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-users me-1"></i> Lihat Peserta
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-tasks text-muted" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Quiz</h4>
                    <p class="text-muted">Belum ada quiz yang dibuat atau diikuti siswa.</p>
                </div>
            <?php endif; ?>
            
            <div class="mt-4">
                <a href="?page=quiz" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Quiz
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php } ?>