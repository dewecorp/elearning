<?php
require_once 'sweetalert_helper.php';
if(@$_SESSION['admin']) { ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h4 class="mb-0">
            <i class="fas fa-user-plus text-primary"></i> Registrasi Siswa
        </h4>
        <p class="text-muted mb-0">Kelola registrasi siswa baru</p>
    </div>
</div>

<!-- Action buttons -->
<div class="row mb-3">
    <div class="col-md-12">
        <button class="btn btn-primary me-2" onclick="openModal('tambah')">
            <i class="fas fa-plus"></i> Tambah Registrasi
        </button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registrationModal">
            <i class="fas fa-user-plus"></i> Registrasi Baru (Modal)
        </button>
        <a href="./laporan/cetak_print.php?data=siswaregistrasi" target="_blank" class="btn btn-outline-secondary ms-2">
            <i class="fas fa-print"></i> Cetak
        </a>
    </div>
</div>

<!-- Include modal template -->
<?php include 'modal_template.php'; ?>

<!-- Data table -->
<div class="row">
    <div class="col-md-12">
        <div class="modern-card">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="dataswisaregistrasi" data-export="true">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Jenis Kelamin</th>
                                <th>Tahun Masuk</th>
                                <th>Status</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_siswa = mysqli_query($db, "SELECT * FROM tb_siswa WHERE status = 'tidak aktif' ORDER BY id_siswa DESC");
                            $no = 1;
                            while($data_siswa = mysqli_fetch_array($sql_siswa)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $data_siswa['nama_lengkap']; ?></td>
                                <td><?php echo $data_siswa['username']; ?></td>
                                <td><?php echo $data_siswa['email']; ?></td>
                                <td><?php echo $data_siswa['jenis_kelamin']; ?></td>
                                <td><?php echo $data_siswa['thn_masuk']; ?></td>
                                <td>
                                    <?php 
                                    if($data_siswa['status'] == 'tidak aktif') {
                                        echo '<span class="badge bg-warning">Pending</span>';
                                    } elseif($data_siswa['status'] == 'aktif') {
                                        echo '<span class="badge bg-success">Aktif</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary">'.ucfirst($data_siswa['status']).'</span>';
                                    }
                                    ?>
                                </td>
                                <td width="200px">
                                    <button class="btn btn-success btn-sm me-1" onclick="approveStudent('<?php echo $data_siswa['id_siswa']; ?>')">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="rejectStudent('<?php echo $data_siswa['id_siswa']; ?>')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Set the entity process file for the modal
var entityProcessFile = 'siswa';

// Override form content loader for siswa registrasi
function loadFormContent(action, id) {
    let content = '';
    
    if(action === 'tambah') {
        content = `
            <input type="hidden" name="action" value="tambah">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kelas *</label>
                        <select name="id_kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php
                            $kelas = mysqli_query($db, "SELECT * FROM tb_kelas ORDER BY nama_kelas");
                            while($data_kelas = mysqli_fetch_array($kelas)) {
                                echo "<option value='$data_kelas[id_kelas]'>$data_kelas[nama_kelas]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tahun Masuk *</label>
                        <input type="number" name="thn_masuk" class="form-control" placeholder="Tahun Masuk" required>
                    </div>
                </div>
            </div>
        `;
    } else if(action === 'edit') {
        // Load existing data via AJAX or embed directly
        content = `
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="${id}">
            <!-- Form content for edit -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tahun Masuk *</label>
                        <input type="number" name="thn_masuk" class="form-control" placeholder="Tahun Masuk" required>
                    </div>
                </div>
            </div>
        `;
    }
    
    document.getElementById('formContent').innerHTML = content;
}

// Approve student function
function approveStudent(id) {
    Swal.fire({
        title: 'Konfirmasi Approve',
        text: 'Setujui registrasi siswa ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Approve',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?page=siswaregistrasi&action=approve&id=' + id;
        }
    });
}

// Reject student function
function rejectStudent(id) {
    Swal.fire({
        title: 'Konfirmasi Reject',
        text: 'Tolak registrasi siswa ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Reject',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?page=siswaregistrasi&action=reject&id=' + id;
        }
    });
}
</script>

<?php } else { ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        Akses ditolak! Hanya admin yang dapat mengakses halaman ini.
    </div>
<?php } ?>