<?php
require_once 'sweetalert_helper.php';
if(@$_SESSION['admin']) { ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h4 class="mb-0">
            <i class="fas fa-newspaper text-primary"></i> Manajemen Berita
        </h4>
        <p class="text-muted mb-0">Kelola berita dan pengumuman</p>
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
        <a href="./laporan/cetak_print.php?data=berita" target="_blank" class="btn btn-outline-secondary">
            <i class="fas fa-print"></i> Cetak
        </a>
    </div>
</div>

<!-- Berita Table -->
<div class="row">
    <div class="col-md-12">
        <div class="modern-card shadow-sm">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="databerita" data-export="true">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Tanggal Posting</th>
                                <th>Penerbit</th>
                                <th>Status</th>
                                <th width="200px">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $sql_berita = mysqli_query($db, "SELECT * FROM tb_berita ORDER BY tgl_posting DESC") or die ($db->error);
                            if(mysqli_num_rows($sql_berita) > 0) {
                                while($data_berita = mysqli_fetch_array($sql_berita)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $data_berita['judul']; ?></td>
                                <td><?php echo !empty($data_berita['tgl_posting']) ? date('d-m-Y', strtotime($data_berita['tgl_posting'])) : '-'; ?></td>
                                <td><?php echo !empty($data_berita['penerbit']) ? $data_berita['penerbit'] : '-'; ?></td>
                                <td>
                                    <?php 
                                    if($data_berita['status'] == 'aktif') {
                                        echo '<span class="badge bg-success">Aktif</span>';
                                    } else {
                                        echo '<span class="badge bg-warning">Tidak Aktif</span>';
                                    }
                                    ?>
                                </td>
                                <td width="200px">
                                    <button class="btn btn-warning btn-sm me-1" onclick="openModalWithData('edit', <?php echo $data_berita['id_berita']; ?>, <?php echo htmlspecialchars(json_encode($data_berita)); ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete('Yakin akan menghapus berita <?php echo $data_berita['judul']; ?>?', '?page=berita&action=hapus&id=<?php echo $data_berita['id_berita']; ?>')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="6" class="text-center">Tidak ada data berita</td></tr>';
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Override form content loader for berita
function loadFormContent(action, id) {
    console.log('berita loadFormContent called with:', action, id);
    let content = '';
    
    if(action === 'tambah') {
        content = '<input type="hidden" name="action" value="tambah">' +
                '<div class="row g-3">' +
                    '<div class="col-md-12">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Judul Berita *</label>' +
                            '<input type="text" name="judul" class="form-control" required>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="row g-3">' +
                    '<div class="col-md-12">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Isi Berita *</label>' +
                            '<textarea name="isi" class="form-control" rows="5" required></textarea>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="row g-3">' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Penerbit</label>' +
                            '<input type="text" name="penerbit" class="form-control">' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Status</label>' +
                            '<select name="status" class="form-control">' +
                                '<option value="aktif">Aktif</option>' +
                                '<option value="tidak aktif">Tidak Aktif</option>' +
                            '</select>' +
                        '</div>' +
                    '</div>' +
                '</div>';
    } else if(action === 'edit') {
        content = '<input type="hidden" name="action" value="edit">' +
                '<input type="hidden" name="id" value="' + id + '">' +
                '<div class="row g-3">' +
                    '<div class="col-md-12">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Judul Berita *</label>' +
                            '<input type="text" name="judul" id="edit_judul" class="form-control" required>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="row g-3">' +
                    '<div class="col-md-12">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Isi Berita *</label>' +
                            '<textarea name="isi" id="edit_isi" class="form-control" rows="5" required></textarea>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="row g-3">' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Penerbit</label>' +
                            '<input type="text" name="penerbit" id="edit_penerbit" class="form-control">' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Status</label>' +
                            '<select name="status" id="edit_status" class="form-control">' +
                                '<option value="aktif">Aktif</option>' +
                                '<option value="tidak aktif">Tidak Aktif</option>' +
                            '</select>' +
                        '</div>' +
                    '</div>' +
                '</div>';
        
        // Load data from stored variable
        if(window.editData && window.editData.id_berita == id) {
            setTimeout(function() {
                $('#edit_judul').val(window.editData.judul);
                $('#edit_isi').val(window.editData.isi);
                $('#edit_penerbit').val(window.editData.penerbit);
                $('#edit_status').val(window.editData.status);
            }, 100);
        }
    }
    
    document.getElementById('formContent').innerHTML = content;
}

// New function to handle edit with data
function openModalWithData(action, id, data) {
    window.editData = data;
    openModal(action, id.toString());
}
</script>

<?php } else { ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        Akses ditolak! Hanya admin yang dapat mengakses halaman ini.
    </div>
<?php } ?>