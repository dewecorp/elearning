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

?>

<div class="row mb-4">
    <div class="col-md-12">
        <h4 class="mb-0">
            <i class="fas fa-edit text-success"></i> Tambah Soal Essay
        </h4>
        <p class="text-muted mb-0">
            Quiz: <?php echo $data_quiz['judul']; ?> | 
            <?php echo !empty($data_quiz['mapel']) ? $data_quiz['mapel'] : 'Umum'; ?>
        </p>
    </div>
</div>

<div class="modern-card">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-plus-circle text-success me-2"></i>
                Form Soal Essay Baru
            </h5>
            <a href="?page=quiz&action=buatsoal&id=<?php echo $id_tq; ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
    
    <div class="card-body p-4">
        <form method="POST" action="inc/process_quiz.php" class="needs-validation" novalidate>
            <input type="hidden" name="action" value="tambahessay">
            <input type="hidden" name="id_tq" value="<?php echo $id_tq; ?>">
            
            <div class="row g-4">
                <!-- Pertanyaan Section -->
                <div class="col-md-12">
                    <div class="border rounded p-4 bg-light-subtle shadow-sm">
                        <h6 class="text-success mb-4 pb-2 border-bottom">
                            <i class="fas fa-question-circle me-2"></i>
                            Pertanyaan Essay
                        </h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                            <textarea name="pertanyaan" class="form-control form-control-lg" rows="4" placeholder="Masukkan pertanyaan essay..." required></textarea>
                            <div class="invalid-feedback">Pertanyaan harus diisi.</div>
                            <small class="text-muted">Buat pertanyaan yang memerlukan jawaban terbuka dan penjelasan dari siswa</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Gambar (Opsional)</label>
                            <input type="text" name="gambar" class="form-control" placeholder="Nama file gambar (jika ada)">
                            <small class="text-muted">Masukkan nama file gambar jika soal memerlukan ilustrasi</small>
                        </div>
                    </div>
                </div>
                
                <!-- Petunjuk Penilaian Section -->
                <div class="col-md-12">
                    <div class="border rounded p-4 bg-light-subtle shadow-sm">
                        <h6 class="text-success mb-4 pb-2 border-bottom">
                            <i class="fas fa-graduation-cap me-2"></i>
                            Petunjuk Penilaian (Opsional)
                        </h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Petunjuk/Kriteria Penilaian</label>
                            <textarea name="petunjuk" class="form-control" rows="3" placeholder="Masukkan petunjuk atau kriteria penilaian untuk soal ini..."></textarea>
                            <small class="text-muted">Berikan panduan untuk membantu dalam proses koreksi jawaban siswa</small>
                        </div>
                    </div>
                </div>
                
                <!-- Contoh Jawaban Section -->
                <div class="col-md-12">
                    <div class="border rounded p-4 bg-light-subtle shadow-sm">
                        <h6 class="text-success mb-4 pb-2 border-bottom">
                            <i class="fas fa-lightbulb me-2"></i>
                            Contoh Jawaban (Opsional)
                        </h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Contoh Jawaban yang Baik</label>
                            <textarea name="contoh_jawaban" class="form-control" rows="3" placeholder="Masukkan contoh jawaban yang diharapkan..."></textarea>
                            <small class="text-muted">Memberikan contoh jawaban membantu dalam standarisasi penilaian</small>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="col-md-12 mt-5">
                    <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
                        <div>
                            <a href="?page=quiz&action=buatsoal&id=<?php echo $id_tq; ?>" class="btn btn-outline-secondary px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali
                            </a>
                        </div>
                        <div>
                            <button type="reset" class="btn btn-outline-danger me-3 px-4 py-2">
                                <i class="fas fa-undo me-2"></i>
                                Reset
                            </button>
                            <button type="submit" class="btn btn-success px-4 py-2">
                                <i class="fas fa-save me-2"></i>
                                Simpan Soal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php } ?>