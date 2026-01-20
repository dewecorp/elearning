<?php
@session_start();
require_once 'sweetalert_helper.php';
if(@$_SESSION['admin']) { ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h4 class="mb-0">
            <i class="fas fa-chalkboard text-primary"></i> Manajemen Kelas
        </h4>
        <p class="text-muted mb-0">Kelola data kelas untuk pembelajaran</p>
    </div>
</div>

<!-- Include modal template -->
<?php include 'modal_template.php'; ?>

<!-- Action buttons -->
<div class="row mb-3">
    <div class="col-md-12">
        <button class="btn btn-primary" onclick="openModal('tambah')">
            <i class="fas fa-plus"></i> Tambah Data
        </button>
        <a href="./laporan/cetak_print.php?data=kelas" target="_blank" class="btn btn-outline-secondary">
            <i class="fas fa-print"></i> Cetak
        </a>
    </div>
</div>

<!-- Data table -->
<div class="row">
    <div class="col-md-12">
        <div class="modern-card shadow-sm">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="datakelas" data-export="true">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kelas</th>
                                <th>Ruang</th>
                                <th>Wali Kelas</th>
                                <th>Ketua Kelas</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_kelas = mysqli_query($db, "SELECT * FROM tb_kelas");
                            $no = 1;
                            while($data_kelas = mysqli_fetch_array($sql_kelas)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $data_kelas['nama_kelas']; ?></td>
                                <td><?php echo $data_kelas['ruang']; ?></td>
                                <?php
                                $sql_tampil_guru = tampil_per_id("tb_pengajar", "id_pengajar = '$data_kelas[wali_kelas]'");
                                $data_tampil_guru = mysqli_fetch_array($sql_tampil_guru);
                                $cek_tampil_guru = mysqli_num_rows($sql_tampil_guru);
                                if($cek_tampil_guru > 0) {
                                    echo "<td>".$data_tampil_guru['nama_lengkap']."</td>";
                                } else {
                                    echo "<td>Belum diatur</td>";
                                }
                                ?>
                                <?php
                                $sql_tampil_siswa = tampil_per_id("tb_siswa", "id_siswa = '$data_kelas[ketua_kelas]'");
                                $data_tampil_siswa = mysqli_fetch_array($sql_tampil_siswa);
                                $cek_tampil_siswa = mysqli_num_rows($sql_tampil_siswa);
                                if($cek_tampil_siswa > 0) {
                                    echo "<td>".$data_tampil_siswa['nama_lengkap']."</td>";
                                } else {
                                    echo "<td>Belum diatur</td>";
                                }
                                ?>
                                <td align="center" width="200px">
                                    <button class="btn btn-warning btn-sm me-1" onclick="openModal('edit', '<?php echo $data_kelas['id_kelas']; ?>')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm me-1" onclick="confirmDelete('Yakin akan menghapus data kelas <?php echo $data_kelas['nama_kelas']; ?>?', '?page=kelas&action=hapus&id=<?php echo $data_kelas['id_kelas']; ?>')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    <a href="?page=siswa&IDkelas=<?php echo $data_kelas['id_kelas']; ?>&kelas=<?php echo $data_kelas['nama_kelas']; ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-users"></i> Siswa
                                    </a>
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
var entityProcessFile = 'kelas';

// Override form content loader for kelas
function loadFormContent(action, id) {
    let content = '';
    
    if(action === 'tambah') {
        // Fetch pengajar options for wali kelas
        fetch('./inc/get_available_pengajar.php')
        .then(response => response.json())
        .then(pengajarOptions => {
            let waliKelasSelect = '<select name="wali_kelas" class="form-control"><option value="">-- Pilih Wali Kelas --</option>';
            
            pengajarOptions.forEach(pengajar => {
                waliKelasSelect += '<option value="' + pengajar.id_pengajar + '">' + pengajar.nama_lengkap + '</option>';
            });
            
            waliKelasSelect += '</select>';
            
            // Fetch siswa options for ketua kelas
            fetch('./inc/get_available_siswa.php')
            .then(response => response.json())
            .then(siswaOptions => {
                let ketuaKelasSelect = '<select name="ketua_kelas" class="form-control"><option value="">-- Pilih Ketua Kelas --</option>';
                
                siswaOptions.forEach(siswa => {
                    ketuaKelasSelect += '<option value="' + siswa.id_siswa + '">' + siswa.nama_lengkap + '</option>';
                });
                
                ketuaKelasSelect += '</select>';
                
                content = `<input type="hidden" name="action" value="tambah">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Kelas *</label>
                                <input type="text" name="nama_kelas" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ruang *</label>
                                <input type="text" name="ruang" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Wali Kelas</label>
                                ` + waliKelasSelect + `
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ketua Kelas</label>
                                ` + ketuaKelasSelect + `
                            </div>
                        </div>
                    </div>`;
                
                document.getElementById('formContent').innerHTML = content;
            })
            .catch(error => {
                console.error('Error fetching siswa options:', error);
                // Fallback to basic options
                let ketuaKelasSelect = '<select name="ketua_kelas" class="form-control"><option value="">-- Pilih Ketua Kelas --</option></select>';
                
                content = `<input type="hidden" name="action" value="tambah">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Kelas *</label>
                                <input type="text" name="nama_kelas" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ruang *</label>
                                <input type="text" name="ruang" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Wali Kelas</label>
                                ` + waliKelasSelect + `
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ketua Kelas</label>
                                ` + ketuaKelasSelect + `
                            </div>
                        </div>
                    </div>`;
                
                document.getElementById('formContent').innerHTML = content;
            });
        })
        .catch(error => {
            console.error('Error fetching pengajar options:', error);
            // Fallback to basic options
            let waliKelasSelect = '<select name="wali_kelas" class="form-control"><option value="">-- Pilih Wali Kelas --</option></select>';
            let ketuaKelasSelect = '<select name="ketua_kelas" class="form-control"><option value="">-- Pilih Ketua Kelas --</option></select>';
            
            content = `<input type="hidden" name="action" value="tambah">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Kelas *</label>
                            <input type="text" name="nama_kelas" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Ruang *</label>
                            <input type="text" name="ruang" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Wali Kelas</label>
                            ` + waliKelasSelect + `
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Ketua Kelas</label>
                            ` + ketuaKelasSelect + `
                        </div>
                    </div>
                </div>`;
            
            document.getElementById('formContent').innerHTML = content;
        });
        document.getElementById('formContent').innerHTML = content;
    } else if(action === 'edit') {
        // Fetch existing data for edit mode
        fetch('./inc/get_kelas_data.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if(data.error) {
                autoError(data.error);
                return;
            }
            
            // Fetch pengajar options for wali kelas
            fetch('./inc/get_available_pengajar.php')
            .then(response => response.json())
            .then(pengajarOptions => {
                let waliKelasSelect = '<select name="wali_kelas" class="form-control"><option value="">-- Pilih Wali Kelas --</option>';
                
                pengajarOptions.forEach(pengajar => {
                    let selected = (pengajar.id_pengajar == data.wali_kelas) ? 'selected' : '';
                    waliKelasSelect += '<option value="' + pengajar.id_pengajar + '" ' + selected + '>' + pengajar.nama_lengkap + '</option>';
                });
                
                waliKelasSelect += '</select>';
                
                // Fetch siswa options for ketua kelas
                fetch('./inc/get_available_siswa.php')
                .then(response => response.json())
                .then(siswaOptions => {
                    let ketuaKelasSelect = '<select name="ketua_kelas" class="form-control"><option value="">-- Pilih Ketua Kelas --</option>';
                    
                    siswaOptions.forEach(siswa => {
                        let selected = (siswa.id_siswa == data.ketua_kelas) ? 'selected' : '';
                        ketuaKelasSelect += '<option value="' + siswa.id_siswa + '" ' + selected + '>' + siswa.nama_lengkap + '</option>';
                    });
                    
                    ketuaKelasSelect += '</select>';
                    
                    content = `<input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="${id}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Kelas *</label>
                                    <input type="text" name="nama_kelas" class="form-control" value="${data.nama_kelas || ''}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ruang *</label>
                                    <input type="text" name="ruang" class="form-control" value="${data.ruang || ''}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Wali Kelas</label>
                                    ` + waliKelasSelect + `
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ketua Kelas</label>
                                    ` + ketuaKelasSelect + `
                                </div>
                            </div>
                        </div>`;
                    
                    document.getElementById('formContent').innerHTML = content;
                })
                .catch(error => {
                    console.error('Error fetching siswa options:', error);
                    // Fallback to basic options
                    let ketuaKelasSelect = '<select name="ketua_kelas" class="form-control"><option value="" ' + (data.ketua_kelas == 0 || data.ketua_kelas == '' ? 'selected' : '') + '>-- Pilih Ketua Kelas --</option></select>';
                    
                    content = `<input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="${id}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Kelas *</label>
                                    <input type="text" name="nama_kelas" class="form-control" value="${data.nama_kelas || ''}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ruang *</label>
                                    <input type="text" name="ruang" class="form-control" value="${data.ruang || ''}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Wali Kelas</label>
                                    ` + waliKelasSelect + `
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ketua Kelas</label>
                                    ` + ketuaKelasSelect + `
                                </div>
                            </div>
                        </div>`;
                    
                    document.getElementById('formContent').innerHTML = content;
                });
            })
            .catch(error => {
                console.error('Error fetching pengajar options:', error);
                // Fallback to basic options
                let waliKelasSelect = '<select name="wali_kelas" class="form-control"><option value="" ' + (data.wali_kelas == 0 || data.wali_kelas == '' ? 'selected' : '') + '>-- Pilih Wali Kelas --</option></select>';
                let ketuaKelasSelect = '<select name="ketua_kelas" class="form-control"><option value="" ' + (data.ketua_kelas == 0 || data.ketua_kelas == '' ? 'selected' : '') + '>-- Pilih Ketua Kelas --</option></select>';
                
                content = `<input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="${id}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Kelas *</label>
                                <input type="text" name="nama_kelas" class="form-control" value="${data.nama_kelas || ''}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ruang *</label>
                                <input type="text" name="ruang" class="form-control" value="${data.ruang || ''}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Wali Kelas</label>
                                ` + waliKelasSelect + `
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ketua Kelas</label>
                                ` + ketuaKelasSelect + `
                            </div>
                        </div>
                    </div>`;
                
                document.getElementById('formContent').innerHTML = content;
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            autoError('Gagal memuat data untuk diedit');
        });
        return; // Exit early since we're handling async operation
    }
}
</script>

<?php } else { ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        Akses ditolak! Hanya admin yang dapat mengakses halaman ini.
    </div>
<?php } ?>