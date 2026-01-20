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
            <i class="fas fa-list-ol text-primary"></i> Tambah Soal Pilihan Ganda
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
                <i class="fas fa-plus-circle text-primary me-2"></i>
                Form Soal Pilihan Ganda Baru
            </h5>
            <a href="?page=quiz&action=buatsoal&id=<?php echo $id_tq; ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
    
    <div class="card-body p-4">
        <form method="POST" action="inc/process_quiz.php" class="needs-validation" novalidate>
            <input type="hidden" name="action" value="tambahpilgan">
            <input type="hidden" name="id_tq" value="<?php echo $id_tq; ?>">
            
            <div class="row g-4">
                <!-- Pertanyaan Section -->
                <div class="col-md-12">
                    <div class="border rounded p-4 bg-light-subtle shadow-sm">
                        <h6 class="text-primary mb-4 pb-2 border-bottom">
                            <i class="fas fa-question-circle me-2"></i>
                            Pertanyaan
                        </h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                            <textarea name="pertanyaan" class="form-control form-control-lg" rows="3" placeholder="Masukkan pertanyaan..." required></textarea>
                            <div class="invalid-feedback">Pertanyaan harus diisi.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Gambar (Opsional)</label>
                            <input type="text" name="gambar" class="form-control" placeholder="Nama file gambar (jika ada)">
                            <small class="text-muted">Masukkan nama file gambar jika soal memerlukan ilustrasi</small>
                        </div>
                    </div>
                </div>
                
                <!-- Pilihan Jawaban Section -->
                <div class="col-md-12">
                    <div class="border rounded p-4 bg-light-subtle shadow-sm">
                        <h6 class="text-primary mb-4 pb-2 border-bottom">
                            <i class="fas fa-list me-2"></i>
                            Pilihan Jawaban
                        </h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilihan A <span class="text-danger">*</span></label>
                            <input type="text" name="pil_a" class="form-control" placeholder="Masukkan pilihan A..." required>
                            <div class="invalid-feedback">Pilihan A harus diisi.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilihan B <span class="text-danger">*</span></label>
                            <input type="text" name="pil_b" class="form-control" placeholder="Masukkan pilihan B..." required>
                            <div class="invalid-feedback">Pilihan B harus diisi.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilihan C <span class="text-danger">*</span></label>
                            <input type="text" name="pil_c" class="form-control" placeholder="Masukkan pilihan C..." required>
                            <div class="invalid-feedback">Pilihan C harus diisi.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilihan D <span class="text-danger">*</span></label>
                            <input type="text" name="pil_d" class="form-control" placeholder="Masukkan pilihan D..." required>
                            <div class="invalid-feedback">Pilihan D harus diisi.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilihan E (Opsional)</label>
                            <input type="text" name="pil_e" class="form-control" placeholder="Masukkan pilihan E (opsional)">
                        </div>
                    </div>
                </div>
                
                <!-- Kunci Jawaban Section -->
                <div class="col-md-12">
                    <div class="border rounded p-4 bg-light-subtle shadow-sm">
                        <h6 class="text-primary mb-4 pb-2 border-bottom">
                            <i class="fas fa-key me-2"></i>
                            Kunci Jawaban
                        </h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Kunci Jawaban <span class="text-danger">*</span></label>
                            <select name="kunci" class="form-select form-select-lg" required>
                                <option value="" selected disabled>-- Pilih Kunci Jawaban --</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                            </select>
                            <div class="invalid-feedback">Silakan pilih kunci jawaban.</div>
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
                            <button type="submit" class="btn btn-primary px-4 py-2">
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