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
            <i class="fas fa-tasks text-primary"></i> Buat Soal/Tugas
        </h4>
        <p class="text-muted mb-0">Pilih jenis soal atau tugas yang akan dibuat</p>
    </div>
</div>

<div class="modern-card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0">
            <i class="fas fa-plus-circle text-primary me-2"></i>
            Form Pembuatan Soal
        </h5>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="?page=quiz" class="needs-validation" novalidate>
            <div class="row g-4">
                <!-- Question Type Selection -->
                <div class="col-md-12">
                    <div class="border rounded p-4 bg-light-subtle shadow-sm">
                        <h6 class="text-primary mb-4 pb-2 border-bottom">
                            <i class="fas fa-list-check me-2"></i>
                            Jenis Soal
                        </h6>
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-3">Pilih Jenis Soal <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check border rounded p-3 h-100">
                                        <input class="form-check-input" type="radio" name="jenis_soal" id="jenis_quiz" value="quiz" required checked>
                                        <label class="form-check-label d-flex flex-column h-100" for="jenis_quiz">
                                            <h6 class="mb-2 text-primary">
                                                <i class="fas fa-folder-plus me-2"></i>
                                                Topik Quiz
                                            </h6>
                                            <small class="text-muted">Buat topik quiz terlebih dahulu, kemudian tambahkan soal-soal di dalamnya</small>
                                            <div class="mt-auto pt-2">
                                                <span class="badge bg-primary">Langkah 1</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check border rounded p-3 h-100">
                                        <input class="form-check-input" type="radio" name="jenis_soal" id="jenis_pilgan" value="pilgan" required>
                                        <label class="form-check-label d-flex flex-column h-100" for="jenis_pilgan">
                                            <h6 class="mb-2 text-success">
                                                <i class="fas fa-list-ol me-2"></i>
                                                Pilihan Ganda
                                            </h6>
                                            <small class="text-muted">Buat soal pilihan ganda langsung dengan 5 pilihan jawaban</small>
                                            <div class="mt-auto pt-2">
                                                <span class="badge bg-success">Langsung</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check border rounded p-3 h-100">
                                        <input class="form-check-input" type="radio" name="jenis_soal" id="jenis_essay" value="essay" required>
                                        <label class="form-check-label d-flex flex-column h-100" for="jenis_essay">
                                            <h6 class="mb-2 text-info">
                                                <i class="fas fa-edit me-2"></i>
                                                Essay
                                            </h6>
                                            <small class="text-muted">Buat soal essay dengan jawaban terbuka untuk dikoreksi manual</small>
                                            <div class="mt-auto pt-2">
                                                <span class="badge bg-info">Langsung</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Dynamic Content Based on Selection -->
                <div id="quiz-section" class="col-md-12 jenis-section">
                    <div class="border rounded p-4 bg-light-subtle shadow-sm">
                        <h6 class="text-primary mb-4 pb-2 border-bottom">
                            <i class="fas fa-folder me-2"></i>
                            Informasi Topik Quiz
                        </h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Quiz <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control form-control-lg" placeholder="Masukkan judul quiz..." required>
                            <div class="invalid-feedback">Judul quiz harus diisi.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi atau instruksi quiz..."></textarea>
                        </div>
                    </div>
                </div>
                
                <div id="pilgan-section" class="col-md-12 jenis-section d-none">
                    <div class="border rounded p-4 bg-light-subtle shadow-sm">
                        <h6 class="text-success mb-4 pb-2 border-bottom">
                            <i class="fas fa-list-ol me-2"></i>
                            Soal Pilihan Ganda
                        </h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                            <textarea name="pertanyaan_pilgan" class="form-control" rows="3" placeholder="Masukkan pertanyaan pilihan ganda..." required></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pilihan A <span class="text-danger">*</span></label>
                                <input type="text" name="pil_a" class="form-control" placeholder="Pilihan jawaban A..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pilihan B <span class="text-danger">*</span></label>
                                <input type="text" name="pil_b" class="form-control" placeholder="Pilihan jawaban B..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pilihan C <span class="text-danger">*</span></label>
                                <input type="text" name="pil_c" class="form-control" placeholder="Pilihan jawaban C..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pilihan D <span class="text-danger">*</span></label>
                                <input type="text" name="pil_d" class="form-control" placeholder="Pilihan jawaban D..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pilihan E <span class="text-danger">*</span></label>
                                <input type="text" name="pil_e" class="form-control" placeholder="Pilihan jawaban E..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kunci Jawaban <span class="text-danger">*</span></label>
                                <select name="kunci" class="form-select" required>
                                    <option value="" selected disabled>-- Pilih Kunci --</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="essay-section" class="col-md-12 jenis-section d-none">
                    <div class="border rounded p-4 bg-light-subtle shadow-sm">
                        <h6 class="text-info mb-4 pb-2 border-bottom">
                            <i class="fas fa-edit me-2"></i>
                            Soal Essay
                        </h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pertanyaan Essay <span class="text-danger">*</span></label>
                            <textarea name="pertanyaan_essay" class="form-control" rows="4" placeholder="Masukkan pertanyaan essay..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Petunjuk Penilaian (Opsional)</label>
                            <textarea name="petunjuk_penilaian" class="form-control" rows="2" placeholder="Masukkan petunjuk penilaian untuk koreksi..."></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Common Fields for All Types -->
                <div class="col-md-12">
                    <div class="border rounded p-4 bg-light-subtle shadow-sm">
                        <h6 class="text-primary mb-4 pb-2 border-bottom">
                            <i class="fas fa-cog me-2"></i>
                            Pengaturan Umum
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                                <select name="id_mapel" class="form-select form-select-lg" required>
                                    <option value="" selected disabled>-- Pilih Mata Pelajaran --</option>
                                    <?php
                                    $mapel = mysqli_query($db, "SELECT * FROM tb_mapel ORDER BY mapel");
                                    while($data_mapel = mysqli_fetch_array($mapel)) {
                                        echo "<option value='$data_mapel[id]'>$data_mapel[mapel]</option>";
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">Silakan pilih mata pelajaran.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kelas Target <span class="text-danger">*</span></label>
                                <select name="id_kelas" class="form-select form-select-lg" required>
                                    <option value="" selected disabled>-- Pilih Kelas --</option>
                                    <?php
                                    $kelas = mysqli_query($db, "SELECT * FROM tb_kelas ORDER BY nama_kelas");
                                    while($data_kelas = mysqli_fetch_array($kelas)) {
                                        echo "<option value='$data_kelas[id_kelas]'>$data_kelas[nama_kelas]</option>";
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">Silakan pilih kelas.</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="col-md-12 mt-5">
                    <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
                        <div>
                            <a href="?page=quiz" class="btn btn-outline-secondary px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali
                            </a>
                        </div>
                        <div>
                            <button type="reset" class="btn btn-outline-danger me-3 px-4 py-2" onclick="resetForm()">
                                <i class="fas fa-undo me-2"></i>
                                Reset
                            </button>
                            <button type="submit" name="action" value="simpanquiz" id="submit-btn" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-save me-2"></i>
                                <span id="submit-text">Simpan Quiz</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Show/hide sections based on selection
document.addEventListener('DOMContentLoaded', function() {
    const jenisRadios = document.querySelectorAll('input[name="jenis_soal"]');
    const sections = document.querySelectorAll('.jenis-section');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    
    function updateForm() {
        const selectedValue = document.querySelector('input[name="jenis_soal"]:checked').value;
        
        // Hide all sections
        sections.forEach(section => section.classList.add('d-none'));
        
        // Show selected section
        if(selectedValue === 'quiz') {
            document.getElementById('quiz-section').classList.remove('d-none');
            submitText.textContent = 'Simpan Quiz';
            submitBtn.name = 'action';
            submitBtn.value = 'simpanquiz';
        } else if(selectedValue === 'pilgan') {
            document.getElementById('pilgan-section').classList.remove('d-none');
            submitText.textContent = 'Simpan Soal PG';
            submitBtn.name = 'action';
            submitBtn.value = 'simpansoalpg';
        } else if(selectedValue === 'essay') {
            document.getElementById('essay-section').classList.remove('d-none');
            submitText.textContent = 'Simpan Soal Essay';
            submitBtn.name = 'action';
            submitBtn.value = 'simpansoalesay';
        }
        
        // Update required fields
        updateRequiredFields(selectedValue);
    }
    
    function updateRequiredFields(type) {
        // Reset all required attributes
        document.querySelectorAll('[name^="pertanyaan"]').forEach(field => {
            field.removeAttribute('required');
        });
        
        // Set required based on type
        if(type === 'pilgan') {
            document.querySelector('[name="pertanyaan_pilgan"]').setAttribute('required', 'required');
        } else if(type === 'essay') {
            document.querySelector('[name="pertanyaan_essay"]').setAttribute('required', 'required');
        }
    }
    
    // Add event listeners
    jenisRadios.forEach(radio => {
        radio.addEventListener('change', updateForm);
    });
    
    // Initialize form
    updateForm();
});

function resetForm() {
    setTimeout(() => {
        document.querySelector('input[name="jenis_soal"][value="quiz"]').click();
    }, 100);
}
</script>

<?php } ?>