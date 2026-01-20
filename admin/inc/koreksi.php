<?php
require_once 'sweetalert_helper.php';
if(@$_SESSION['admin']) { 

// Get parameters
$id_tq = @$_GET['id_tq'];
$id_siswa = @$_GET['id_siswa'];

// Validate parameters
if(!$id_tq || !$id_siswa) {
    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Parameter tidak lengkap!</div>';
    return;
}

// Get quiz information
$quiz_query = mysqli_query($db, "SELECT tq.*, m.mapel FROM tb_topik_quiz tq LEFT JOIN tb_mapel m ON tq.id_mapel = m.id WHERE tq.id_tq = '$id_tq'");
$quiz_data = mysqli_fetch_array($quiz_query);

if(!$quiz_data) {
    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Quiz tidak ditemukan!</div>';
    return;
}

// Get student information
$student_query = mysqli_query($db, "SELECT * FROM tb_siswa WHERE id_siswa = '$id_siswa'");
$student_data = mysqli_fetch_array($student_query);

if(!$student_data) {
    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Siswa tidak ditemukan!</div>';
    return;
}

// Get essay questions and answers for this quiz and student
$questions_query = mysqli_query($db, "
    SELECT se.*, j.jawaban 
    FROM tb_soal_essay se 
    LEFT JOIN tb_jawaban j ON se.id_essay = j.id_soal AND j.id_siswa = '$id_siswa' AND j.id_tq = '$id_tq'
    WHERE se.id_tq = '$id_tq'
    ORDER BY se.id_essay ASC
");

// Get existing score if exists
$score_query = mysqli_query($db, "SELECT * FROM tb_nilai_essay WHERE id_tq = '$id_tq' AND id_siswa = '$id_siswa'");
$existing_score = mysqli_fetch_array($score_query);

?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <a href="?page=quiz" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Kembali ke Quiz
            </a>
        </div>
        <h4 class="mb-0">
            <i class="fas fa-edit text-primary"></i> Koreksi Essay - <?php echo $student_data['nama_lengkap']; ?>
        </h4>
        <p class="text-muted mb-0">
            <?php echo $quiz_data['judul']; ?> | 
            <?php echo !empty($quiz_data['mapel']) ? $quiz_data['mapel'] : 'Umum'; ?> |
            NIS: <?php echo $student_data['nis']; ?>
        </p>
    </div>
</div>

<div class="modern-card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0">
            <i class="fas fa-file-alt me-2 text-primary"></i>
            Jawaban Essay Siswa
        </h5>
    </div>
    <div class="card-body p-4">
        <?php if(mysqli_num_rows($questions_query) > 0): ?>
            <form class="koreksi-form">
                <input type="hidden" name="action" value="koreksiessay">
                <input type="hidden" name="id_tq" value="<?php echo $id_tq; ?>">
                <input type="hidden" name="id_siswa" value="<?php echo $id_siswa; ?>">
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <h6 class="text-primary mb-2"><i class="fas fa-user me-2"></i>Data Siswa</h6>
                            <p class="mb-1"><strong>Nama:</strong> <?php echo $student_data['nama_lengkap']; ?></p>
                            <p class="mb-1"><strong>NIS:</strong> <?php echo $student_data['nis']; ?></p>
                            <p class="mb-0"><strong>Kelas:</strong> <?php echo $student_data['id_kelas']; ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <h6 class="text-primary mb-2"><i class="fas fa-graduation-cap me-2"></i>Data Quiz</h6>
                            <p class="mb-1"><strong>Judul:</strong> <?php echo $quiz_data['judul']; ?></p>
                            <p class="mb-1"><strong>Mata Pelajaran:</strong> <?php echo !empty($quiz_data['mapel']) ? $quiz_data['mapel'] : '-'; ?></p>
                            <p class="mb-0"><strong>Tanggal:</strong> <?php echo date('d-m-Y', strtotime($quiz_data['tgl_buat'])); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5 class="text-primary mb-3"><i class="fas fa-list me-2"></i>Soal dan Jawaban</h5>
                    
                    <?php 
                    $no = 1;
                    while($question = mysqli_fetch_array($questions_query)): 
                    ?>
                    <div class="border rounded p-4 mb-4 bg-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="text-dark mb-0">Soal #<?php echo $no++; ?></h6>
                            <?php if(!empty($question['gambar'])): ?>
                                <span class="badge bg-info">Ada Gambar</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pertanyaan:</label>
                            <div class="border rounded p-3 bg-light-subtle">
                                <?php echo nl2br(htmlspecialchars($question['pertanyaan'])); ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jawaban Siswa:</label>
                            <?php if(!empty($question['jawaban'])): ?>
                                <div class="border rounded p-3 bg-success-subtle">
                                    <?php echo nl2br(htmlspecialchars($question['jawaban'])); ?>
                                </div>
                            <?php else: ?>
                                <div class="border rounded p-3 bg-warning-subtle text-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Siswa belum menjawab soal ini
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                
                <div class="border rounded p-4 bg-primary-subtle">
                    <h5 class="text-primary mb-3"><i class="fas fa-star me-2"></i>Penilaian</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nilai Essay <span class="text-danger">*</span></label>
                                <input type="number" name="nilai" class="form-control form-control-lg" 
                                       placeholder="Masukkan nilai (0-100)" min="0" max="100" step="0.1"
                                       value="<?php echo !empty($existing_score['nilai']) ? $existing_score['nilai'] : ''; ?>" required>
                                <div class="form-text">Nilai dalam skala 0-100</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status Penilaian</label>
                                <?php if(!empty($existing_score['nilai'])): ?>
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Sudah dinilai: <strong><?php echo $existing_score['nilai']; ?></strong>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-clock me-2"></i>
                                        Belum dinilai
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan/Komentar (Opsional)</label>
                        <textarea name="catatan" class="form-control" rows="3" 
                                  placeholder="Tambahkan catatan atau komentar untuk siswa..."></textarea>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
                    <a href="?page=quiz&action=pesertakoreksi&id_tq=<?php echo $id_tq; ?>" class="btn btn-outline-secondary px-4 py-2">
                        <i class="fas fa-arrow-left me-2"></i>
                        Kembali ke Daftar Peserta
                    </a>
                    
                    <div>
                        <button type="reset" class="btn btn-outline-danger me-3 px-4 py-2">
                            <i class="fas fa-undo me-2"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-save me-2"></i>
                            <?php echo !empty($existing_score['nilai']) ? 'Update Nilai' : 'Simpan Nilai'; ?>
                        </button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-question-circle text-muted" style="font-size: 3rem;"></i>
                <h4 class="mt-3 text-muted">Tidak Ada Soal Essay</h4>
                <p class="text-muted">Quiz ini tidak memiliki soal essay untuk dikoreksi.</p>
                <a href="?page=quiz&action=pesertakoreksi&id_tq=<?php echo $id_tq; ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Daftar Peserta
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission with AJAX
    const form = document.querySelector('.koreksi-form');
    if(form) {
        console.log('Koreksi form found');
        
        form.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
            e.preventDefault();
            e.stopPropagation(); // Prevent any other handlers
            
            const formData = new FormData(this);
            
            // Log form data for debugging
            console.log('Form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            // Validate required fields
            const nilai = formData.get('nilai');
            if (!nilai || nilai.trim() === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Nilai harus diisi!'
                });
                return;
            }
            
            // Show loading
            Swal.fire({
                title: 'Menyimpan Nilai...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit via fetch
            fetch('./inc/process_quiz.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response received', response);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received', data);
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reload or redirect
                        window.location.href = '?page=quiz&action=pesertakoreksi&id_tq=<?php echo $id_tq; ?>';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error caught:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menyimpan nilai: ' + error.message,
                    confirmButtonText: 'OK'
                });
            });
        });
    } else {
        console.log('Koreksi form not found');
    }
});
</script>

<?php } ?>