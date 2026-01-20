<?php
require_once 'sweetalert_helper.php';
if(@$_SESSION['admin']) { 

// Get quiz topic ID
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

// Get multiple choice questions
$pilgan_questions = mysqli_query($db, "SELECT * FROM tb_soal_pilgan WHERE id_tq = '$id_tq' ORDER BY id_pilgan ASC");

// Get essay questions  
$essay_questions = mysqli_query($db, "SELECT * FROM tb_soal_essay WHERE id_tq = '$id_tq' ORDER BY id_essay ASC");

?>

<div class="row mb-4">
    <div class="col-md-12">
        <h4 class="mb-0">
            <i class="fas fa-list text-primary"></i> Daftar Soal - <?php echo $data_quiz['judul']; ?>
        </h4>
        <p class="text-muted mb-0">
            <?php echo !empty($data_quiz['mapel']) ? $data_quiz['mapel'] : 'Umum'; ?> | 
            <?php echo date('d-m-Y', strtotime($data_quiz['tgl_buat'])); ?>
        </p>
    </div>
</div>

<div class="modern-card">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-tasks text-primary me-2"></i>
                Detail Soal Quiz
            </h5>
            <div>
                <a href="?page=quiz&action=daftarsoal" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <a href="?page=quiz&action=buatsoal&id=<?php echo $id_tq; ?>" class="btn btn-primary btn-sm ms-2">
                    <i class="fas fa-plus me-1"></i> Tambah Soal
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Summary Info -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="border rounded p-3 text-center bg-light">
                    <h3 class="text-primary mb-1"><?php echo mysqli_num_rows($pilgan_questions); ?></h3>
                    <p class="mb-0 text-muted">Soal Pilihan Ganda</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center bg-light">
                    <h3 class="text-success mb-1"><?php echo mysqli_num_rows($essay_questions); ?></h3>
                    <p class="mb-0 text-muted">Soal Essay</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center bg-light">
                    <h3 class="text-info mb-1"><?php echo (mysqli_num_rows($pilgan_questions) + mysqli_num_rows($essay_questions)); ?></h3>
                    <p class="mb-0 text-muted">Total Soal</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center bg-light">
                    <h3 class="text-warning mb-1"><?php echo floor($data_quiz['waktu_soal']/60); ?></h3>
                    <p class="mb-0 text-muted">Menit</p>
                </div>
            </div>
        </div>

        <!-- Multiple Choice Questions -->
        <?php if(mysqli_num_rows($pilgan_questions) > 0): ?>
        <div class="mb-5">
            <h5 class="mb-3 pb-2 border-bottom">
                <i class="fas fa-list-ol text-primary me-2"></i>
                Soal Pilihan Ganda (<?php echo mysqli_num_rows($pilgan_questions); ?> soal)
            </h5>
            <div class="accordion" id="pilganAccordion">
                <?php 
                $no_pilgan = 1;
                while($question = mysqli_fetch_array($pilgan_questions)): 
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingPilgan<?php echo $question['id_pilgan']; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapsePilgan<?php echo $question['id_pilgan']; ?>">
                            <strong>Soal #<?php echo $no_pilgan++; ?>:</strong> 
                            <?php 
                            $pertanyaan = strip_tags($question['pertanyaan']);
                            echo strlen($pertanyaan) > 100 ? substr($pertanyaan, 0, 100) . '...' : $pertanyaan;
                            ?>
                        </button>
                    </h2>
                    <div id="collapsePilgan<?php echo $question['id_pilgan']; ?>" class="accordion-collapse collapse" 
                         data-bs-parent="#pilganAccordion">
                        <div class="accordion-body">
                            <div class="mb-3">
                                <strong>Pertanyaan:</strong>
                                <p class="mt-2"><?php echo nl2br($question['pertanyaan']); ?></p>
                                <?php if(!empty($question['gambar'])): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Gambar: <?php echo $question['gambar']; ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Pilihan Jawaban:</strong>
                                    <ul class="list-unstyled mt-2">
                                        <li><span class="badge bg-secondary me-2">A</span> <?php echo $question['pil_a']; ?></li>
                                        <li><span class="badge bg-secondary me-2">B</span> <?php echo $question['pil_b']; ?></li>
                                        <li><span class="badge bg-secondary me-2">C</span> <?php echo $question['pil_c']; ?></li>
                                        <li><span class="badge bg-secondary me-2">D</span> <?php echo $question['pil_d']; ?></li>
                                        <?php if(!empty($question['pil_e'])): ?>
                                            <li><span class="badge bg-secondary me-2">E</span> <?php echo $question['pil_e']; ?></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3 bg-success-subtle">
                                        <strong class="text-success">Jawaban Benar:</strong>
                                        <h3 class="text-success mt-2"><?php echo strtoupper($question['kunci']); ?></h3>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3 text-muted">
                                <small>Dibuat: <?php echo date('d-m-Y', strtotime($question['tgl_buat'])); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Essay Questions -->
        <?php if(mysqli_num_rows($essay_questions) > 0): ?>
        <div>
            <h5 class="mb-3 pb-2 border-bottom">
                <i class="fas fa-edit text-success me-2"></i>
                Soal Essay (<?php echo mysqli_num_rows($essay_questions); ?> soal)
            </h5>
            <div class="accordion" id="essayAccordion">
                <?php 
                $no_essay = 1;
                while($question = mysqli_fetch_array($essay_questions)): 
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingEssay<?php echo $question['id_essay']; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapseEssay<?php echo $question['id_essay']; ?>">
                            <strong>Soal #<?php echo $no_essay++; ?>:</strong> 
                            <?php 
                            $pertanyaan = strip_tags($question['pertanyaan']);
                            echo strlen($pertanyaan) > 100 ? substr($pertanyaan, 0, 100) . '...' : $pertanyaan;
                            ?>
                        </button>
                    </h2>
                    <div id="collapseEssay<?php echo $question['id_essay']; ?>" class="accordion-collapse collapse" 
                         data-bs-parent="#essayAccordion">
                        <div class="accordion-body">
                            <div class="mb-3">
                                <strong>Pertanyaan:</strong>
                                <p class="mt-2"><?php echo nl2br($question['pertanyaan']); ?></p>
                                <?php if(!empty($question['gambar'])): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Gambar: <?php echo $question['gambar']; ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-3 text-muted">
                                <small>Dibuat: <?php echo date('d-m-Y', strtotime($question['tgl_buat'])); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- No Questions Message -->
        <?php if(mysqli_num_rows($pilgan_questions) == 0 && mysqli_num_rows($essay_questions) == 0): ?>
        <div class="text-center py-5">
            <i class="fas fa-question-circle text-muted" style="font-size: 3rem;"></i>
            <h4 class="mt-3 text-muted">Belum Ada Soal</h4>
            <p class="text-muted">Quiz ini belum memiliki soal. Silakan tambahkan soal terlebih dahulu.</p>
            <a href="?page=quiz&action=buatsoal&id=<?php echo $id_tq; ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Tambah Soal
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php } ?>